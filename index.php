<?php
include "trieur.php";
include "class.php";



// On enregistre notre autoload.
function chargerClasse($classname)
{
    require $classname.'php';
}






spl_autoload_register('chargerClasse');
session_start(); // On appelle session_start() APRÈS avoir enregistré l'autoload.
if (isset($_GET['deconnexion']))
{
    session_destroy();
    header('Location: .');
    exit();
}
 






if (isset($_SESSION['perso'])) // Si la session perso existe, on restaure l'objet.
{
    $perso = $_SESSION['perso'];
}
 







$db = new PDO('mysql:host=192.168.65.227;dbname=MaelDrelonJeuCombat', 'mael', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // On émet une alerte à chaque fois qu'une requête a échoué.
$manager = new PersonnagesManager($db);
 






if (isset($_POST['creer']) && isset($_POST['nom'])) // Si on a voulu créer un personnage.
{
    $perso = new Personnage(['nom' => $_POST['nom']]); // On crée un nouveau personnage.
 
    if (!$perso->nomValide())
    {
        $message = 'Le nom choisi est invalide';
        unset($perso);
    }
    elseif ($manager->exists($perso->nom()))
    {
        $message = 'Le nom du personnage est déja pris';
        unset($perso);
    }
    else
    {
        $manager->add($perso);
    }
}
 
elseif (isset($_POST['Utiliser']) && isset($_POST['nom'])) // Si on a voulu utiliser un personnage.
{
    if ($manager->exists($_POST['nom'])) // Si celui-ci existe.
    {
        $perso = $manager->get($_POST['nom']);
    }
 
    else
    {
        $message = 'Ce personnage n\'existe pas'; // S'il n'existe pas, on affichera ce message.
    }
}
 
elseif (isset($_GET['frapper'])) // Si on a cliqué sur un personnage pour le frapper.
{
    if (!isset($perso))
    {
        $message = 'Merci de créer un personnage ou de vous identifier.'; 
    }
    else
    {
        if (!$manager->exists((int)$_GET['frapper'])) //Si le personnage que vous voulez attaquer n'existe plus ou pas.
        {
            $message = 'Le personnage que vous voulez frapper n\'existe pas !';
        }
     
        else
        {
            $persoAfrapper = $manager->get((int) $_GET['frapper']);
            $retour = $perso->frapper($persoAfrapper);  // On stocke dans $retour les éventuelles erreurs ou messages que renvoie la méthode frapper.
 
            switch ($retour)
            {
                case Personnage::CEST_MOI; //Si l'ID est la même que celle du personnage utiliser
                $message = 'Malin, mais nan.';
                break;
 
                case Personnage::PERSONNAGE_FRAPPE; //Si vous attaquer un personange adverse
                $message = 'Le personnage a bien frapper !';
 
                $perso->gagnerexperience();
 
                $manager->update($perso);
                $manager->update($persoAfrapper);
                break;
 
                case Personnage::PERSONNAGE_TUE; //Si vous tué le personnage adverse
                $message = 'Vous avez tué ce personnage';
 
                $perso->gagnerexperience();

                $manager->update($perso);
                $manager->delete($persoAfrapper);
                break; 
 
                case Personnage::NIVEAU_ATTEINT; //Si vous gagner un niveau
                $message = 'Vous avez atteint le niveau !';
                break;               
            }
        }
    }
}
?>
 
<!-- Fin de préréglage et préparation -->




<!-- Affichage sur la page -->



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
    if (isset($message)) //Si on a un message à afficher 
    {   
        echo '<p>', $message, '</p>'; // Si oui, on l'affiche.
    }
 
    if (isset($perso)) // Si on utilise un personnage ou après en avoir créer un
    {
?>




    <p><a href="?deconnexion=1">Déconnexion</a></p>


      
    <fieldset>
        <legend>Mes information</legend> <!-- Information du personnage -->
        <p>
            <p>Nom : <?= htmlspecialchars($perso->nom()) ?></p>
            <p>Degats : <?= $perso->degats() ?></p>
            <p>Experience : <?= $perso->experience() ?></p>
            <p>Niveau : <?= $perso->lvl() ?></p>
            <p>Force : <?= $perso->puissance() ?></p>
        </p>
    </fieldset>






    <fieldset>
        <legend>Qui frapper?</legend> <!-- Liste de personnage présent -->
        <p>
            <p><?php
                $persos = $manager->getList($perso->nom());
                if (empty($persos))
                {
                    echo 'personnage à frapper';
                }
                else
                {
                    foreach ($persos as $unperso)
                    echo '<a href="?frapper=', $unperso->id(), '">', htmlspecialchars($unperso->nom()),'</a><p> (Force =><strong>', $unperso->puissance(), '</strong>)', '(Degats => <strong>', $unperso->degats(),'</strong> ): ','( Experience =><strong>', $unperso->experience (),')</strong>: ','(Niveau =><strong>', $unperso->lvl(),'</strong>)</p>';
                }
            ?></p>
        </p>
    </fieldset>
    <?php
}
else
{
?>





<!-- Création du personage -->
<div class="espace"></div>
<div id="container">
<div id="center">
  <p>
    <form method="POST">
          NOM: <input type="text" name="nom" maxlength="50">
          <input type="submit" value="Créer ce personnage" name="creer">
          <input type="submit" value="Utiliser ce personnage" name="Utiliser">
    </form>
  </p>
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
 
if (isset($perso))
{
    $_SESSION['perso'] = $perso;
}
