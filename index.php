<?php
include "class.php";
include "trieur.php";


// On enregistre notre autoload.
function chargerClasse($classname)
{
  require $classname.'.php';
}

spl_autoload_register('chargerClasse');

session_start(); // On appelle session_start() APRÈS avoir enregistré l'autoload.

if (isset($_GET['deconnexion']))
{
  session_destroy();
  header('Location: .');
  exit();
}

$db = new PDO('mysql:host=192.168.65.227; dbname=MaelDrelonJeuCombat; charset=utf8','mael', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // On émet une alerte à chaque fois qu'une requête a échoué.

$manager = new PersonnagesManager($db);

if (isset($_SESSION['perso'])) // Si la session perso existe, on restaure l'objet.
{
  $perso = $_SESSION['perso'];
}

if (isset($_POST['creer']) && isset($_POST['nom'])) // Si on a voulu créer un personnage.
{
  $perso = new Personnage(['nom' => $_POST['nom']]); // On crée un nouveau personnage.
  
  if (!$perso->nomValide())
  {
    $message = '<div id="center">Le nom choisi est invalide.</div>';
    unset($perso);
  }
  elseif ($manager->exists($perso->nom()))
  {
    $message = '<div id="center">Le nom du personnage est déjà pris.</div>';
    unset($perso);
  }
  else
  {
    $manager->add($perso);
  }
}

elseif (isset($_POST['utiliser']) && isset($_POST['nom'])) // Si on a voulu utiliser un personnage.
{
  if ($manager->exists($_POST['nom'])) // Si celui-ci existe.
  {
    $perso = $manager->get($_POST['nom']);
  }
  else
  {
    $message = '<div id="center">Ce personnage n\'existe pas !</div>'; // S'il n'existe pas, on affiche ce message.
  }
}

elseif (isset($_GET['frapper'])) // Si on a cliqué sur un personnage pour le frapper.
{
  if (!isset($perso))
  {
    $message = '<div id="center">Merci de créer un personnage ou de vous identifier.</div>';
  }
  else
  {
    if (!$manager->exists((int) $_GET['frapper']))
    {
      $message = '<div id="center">Le personnage que vous voulez frapper n\'existe pas !</div>';
    }
    else
    {
      $persoAFrapper = $manager->get((int) $_GET['frapper']);
      $retour = $perso->frapper($persoAFrapper); // On stocke dans $retour les éventuelles erreurs ou messages que renvoie la méthode frapper.
      switch ($retour)
      {
        case Personnage::CEST_MOI :
          $message = '<div id="center">Je suis plus malin que toi, mais bien essayé.</div>';
          break;
        
        case Personnage::PERSONNAGE_FRAPPE :
          $message = '<div id="center">Le personnage a bien été frappé !</div>';
          
          $perso->gagnerExp();
          $manager->update($perso);
          $manager->update($persoAFrapper);
          
          break;
        
        case Personnage::PERSONNAGE_TUE :   
          $message = '<div id="center">Le personnage est mort !</div>';
          
          $perso->gagnerExp();
          $manager->update($perso);
          $manager->delete($persoAFrapper);?>
            <script>
                alert('Vous avez tué ce personnage !');
            </script>
            <?php
          break;
      }
    }
  }
}
?>


<!DOCTYPE html>
<html>
  <head>
    <title>TP : Mini jeu de combat</title>
    <link rel="stylesheet" href="Css.css"> 
    <script type="text/javascript" src="Horloge.js"></script>
    <meta charset="utf-8" />
  </head>
  <body onLoad="Démarrer()" onUnLoad="Arréter()">
  
    <div id="center"><h1>Nombre de personnages créés : <?= $manager->count() ?></h1></div>
    

<?php
if (isset($message)) // Si on a un message à afficher 
{
  echo '<p>', $message, '</p>'; // Si oui, on l'affiche.
}

if (isset($perso)) // Si on utilise un personnage ou après en avoir créer un.
{
?>


    <p><a href="?deconnexion=1">Déconnexion</a></p>
    
    <fieldset>
        <legend>
            <h2>Mes informations</h2>
        </legend>
      <p>
        <div id="text"><p>Nom : <?= htmlspecialchars($perso->nom()) ?></p>
        <p>Dégâts : <?= $perso->degats() ?></p>
        <p>Expérience : <?= $perso->exp() ?></p>
        <p>Niveau : <?= $perso->lvl() ?></p>
        </div>
      </p>
    </fieldset>
    
    
    <fieldset>
        <legend>
            <h2>Qui frapper ?</h2>
        </legend>
      <p>
<?php
$persos = $manager->getList($perso->nom());

if (empty($persos))
{
  echo 'Personne à frapper !';
}

else
{
  foreach ($persos as $unPerso)
  {
    echo '<p><a href="?frapper=', $unPerso->id(), '">', htmlspecialchars($unPerso->nom()), '</a> (dégâts : ', $unPerso->degats(), ')</p>';
  }
}
?>
    </p>
    </fieldset>


<?php
}
else
{
?>
<div class="espace"></div>
<div id="container">
<div id="center">
    <form action="" method="post">
        <div class="beau">
            Nom : <input type="text" name="nom" maxlength="50" />
            <input type="submit" value="Créer ce personnage" name="creer" />
            <input type="submit" value="Utiliser ce personnage" name="utiliser" />
        </div>
    </form>
</div>
</div>

<?php
}
?>
<div id="horl">
    <p>
    <img src="images/dark green/space.gif" width="15" height="20">
    <img src="images/dark green/space.gif" width="15" height="20">
    <img src="images/dark green/dgon.gif" width="15" height="20">
    <img src="images/dark green/space.gif" width="15" height="20">
    <img src="images/dark green/space.gif" width="15" height="20">
    <img src="images/dark green/dgon.gif" width="15" height="20">
    <img src="images/dark green/space.gif" width="15" height="20">
    <img src="images/dark green/space.gif" width="15" height="20">
    </p>
</div>
  </body>
</html>


<?php
if (isset($perso)) // Si on a créé un personnage, on le stocke dans une variable session afin d'économiser une requête SQL.
{
  $_SESSION['perso'] = $perso;
}
?>
