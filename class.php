<?php
class Personnage
{
    private $_degats,
            $_id,
            $_nom,
            $_lvl,
            $_experience ,
            $_puissance;

             
 
    const CEST_MOI = 1; // Constante renvoyée par la méthode `frapper` si on se frappe soi-même.
    const PERSONNAGE_TUE = 2; // Constante renvoyée par la méthode `frapper` si on a tué le personnage en le frappant.
    const PERSONNAGE_FRAPPE = 3; // Constante renvoyée par la méthode `frapper` si on a bien frappé le personnage.
    const NIVEAU_ATTEINT = 4; // Constante renvoyée par la switch case (retour) si le personnage a gagné un lvl.
 
    public function __construct(array $donnees)
    {
        $this->hydrate($donnees);
    }
 
    public function frapper(Personnage $perso) //Frappe le personnage choisie
    {
        if ($perso->id() == $this->_id)
        {
            return self::CEST_MOI;
        } 
        return $perso->recevoirDegats($this->puissance());//Reçoit la puissance du personnage avec lequel l'utilisateur utilise pour attaquer un adversaire
    }
 
    public function hydrate(array $donnees) //Créer un tableaux permettant de récupérer les données de chaque personnages
    {
        foreach($donnees as $key => $value)
        {
            $method = 'set'.ucfirst($key);
            if (method_exists($this, $method))
            {
                $this->$method($value);
            }
        }
    }
 
    public function recevoirDegats($degats) //Renvoie les dégats à l'ennemie choisie
    {
        $this->_degats += $degats; 
        if ($this->_degats >= 100)
        {
            return self::PERSONNAGE_TUE; //Renvoie le switch pour annoncé la mort de l'adversaire
        }
 
        return self::PERSONNAGE_FRAPPE;  //Renvoie la switch pour annoncé que l'attaque à correctement était effectuer
    }
 
    public function gagnerpuissance() //Gagne un niveau de puissance
    {
        $this->_puissance += 2;
        if ($this->_puissance >= 201)
        {
          $this->setpuissance(201);
        }
    }
     
    public function gagnerexperience () //Gagne de l'expérience
    {
        $this->_experience += 5;
        if ($this->_experience >= 100) //Si exp > 100, ajoute 1 au lvl, 2 en puissance et retourne à 0 l'expérience
        {  
            $this->gagnerlvl();
            $this->gagnerpuissance();
            $this->setexperience (0);
        }
    }
 
    public function gagnerlvl() //Gagne un lvl supplémentaire
    {
        $this->_lvl += 1;
        if ($this->_lvl >= 100) //Si le lvl > 100, retourne 100 au lvl. Cela évite de dépasser au dela de 100.
        {
            $this->setlvl(100);
        }
    }







    //toute les fonctions RETURN $This->
 
    public function degats()
    {
        return $this->_degats;
    }
 
    public function id()
    {
        return $this->_id;
    }
 
    public function nom()
    {
        return $this->_nom;
    }
 
    public function nomValide() //Verifie si le nom est valide 
    {
        return !empty($this->_nom);
    }
 
    public function puissance()
    {
        return $this->_puissance;
    }
 
    public function lvl()
    {
        return $this->_lvl;
    }
 
    public function experience()
    {
        return $this->_experience;
    }







    // Toute les fonctions SET


 
    public function setDegats($degats)
    {
        $degats = (int) $degats;
        if ($degats >= 0 && $degats <= 100)
        {
            $this->_degats = $degats;
        }
    }
 
    public function setpuissance($puissance)
    {
        $puissance = (int) $puissance;
        if ($puissance >= 0 && $puissance <=100)
        {
            $this->_puissance = $puissance;
        }
    }
 
    public function setlvl($lvl)
    {
        $lvl = (int) $lvl;
        if ($lvl >= 0 && $lvl <= 100)
        {
            $this->_lvl = $lvl;
        }
    }
 
    public function setexperience($experience )
    {
        $experience  = (int) $experience ;
        if ($experience  >= 0 && $experience <=100)
        {  
            $this->_experience  = $experience;
        }
    }
 
    public function setId($id)
    {
        $id = (int) $id;
        if ($id > 0)
        {
            $this->_id = $id;
        }
    }
 
    public function setNom($nom)
    {
        if (is_string($nom))
        {
            $this->_nom = $nom;
        }
    }
 
}