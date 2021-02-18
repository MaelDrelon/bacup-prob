<?php

class PersonnagesManager
{
    private $_db;  // Instance de PDO
 



    public function __construct($db)
    {
        $this->setDb($db);
    }
 





    public function add(Personnage $perso) //Ajoute un perso
  {
    $q = $this->_db->prepare('INSERT INTO personnages(nom) VALUES(:nom)');
 
    $q->bindValue(':nom', $perso->nom());
    $q->execute();
     
    $perso->hydrate([         
      'id' => $this->_db->lastInsertId(),              
      'degats' => 0,
      'experience' => 0,
      'lvl' => 1,
      'puissance' => 1,
                     ]);
  }
   
 



    public function count() //Compte les personnages créer
    {
        return $this->_db->query('SELECT COUNT(*) FROM personnages')->fetchColumn();
    }
 





    public function delete(Personnage $perso) //Supprime le perso mort
    {
        $this->_db->exec('DELETE FROM personnages WHERE id = '.$perso->id());
    }
 




    public function exists($info)
    {
        if (is_int($info))  // On veut voir si tel personnage ayant pour id $info existe.
        {
            return (bool) $this->_db->query('SELECT COUNT(*) FROM personnages WHERE id = '.$info)->fetchColumn();
        }
 
        // Sinon, c'est qu'on veut vérifier que le nom existe ou pas.

        $q = $this->_db->prepare('SELECT COUNT(*) FROM personnages WHERE nom = :nom');
        $q->execute([':nom' => $info]);
 
        return (bool) $q->fetchColumn();
    }
 





    public function get($info)  //Récupére les infos du perso
    {
        if (is_int($info))
        {
            $q = $this->_db->query('SELECT * FROM personnages WHERE id = '.$info);
            $donnees = $q->fetch(PDO::FETCH_ASSOC);
 
            return new Personnage($donnees);
        }
        else
        {
            $q = $this->_db->prepare('SELECT * FROM personnages WHERE nom = :nom');
            $q->execute([':nom' => $info]);
 
            return new Personnage($q->fetch(PDO::FETCH_ASSOC));
        }
    }
 





    public function getList($nom) //Récupére une liste des perso
    {
        $persos = [];
        $q = $this->_db->prepare('SELECT * FROM personnages WHERE nom <> :nom ORDER BY nom');
        $q->execute([':nom'=> $nom]);
         
        while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
        {
            $persos[] = new Personnage($donnees);
        }
 
        return $persos;
    }
 




    public function update(Personnage $perso) //Update les dmg du perso touché, ainsi que les points d'expérience et niveaux de l'attaquand.
    {
        $q = $this->_db->prepare('UPDATE personnages SET degats = :degats, experience = :experience, lvl = :lvl, puissance = :puissance WHERE id = :id');
         
        $q->bindValue(':degats', $perso->degats(), PDO::PARAM_INT);
        $q->bindValue(':lvl', $perso->lvl(), PDO::PARAM_INT);
        $q->bindValue(':experience', $perso->experience(), PDO::PARAM_INT);
        $q->bindValue(':puissance', $perso->puissance(), PDO::PARAM_INT);
        $q->bindValue(':id', $perso->id(), PDO::PARAM_INT);
 
        $q->execute();
    }
 





    public function setDb(PDO $db)
    {
        $this->_db = $db;
    }
}