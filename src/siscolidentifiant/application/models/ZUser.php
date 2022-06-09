<?php

class Application_Model_ZUser
{
    private $login;
    private $nom;
    private $prenom;

    public function __construct($row=null){
        if($row) {
            $this->login = $row->login;
            $this->nom = $row->nom;
            $this->prenom = $row->prenom;
        }
    }

    public function setLogin($login){
        $this->login = $login;
    }

    public function getLogin(){
        return $this->login;
    }

    public function setNom($nom){
        $this->nom = $nom;
    }

    public function getNom(){
        return $this->nom;
    }

    public function setPrenom($prenom){
        $this->prenom = $prenom;
    }

    public function getPrenom(){
        return $this->prenom;
    }

}

