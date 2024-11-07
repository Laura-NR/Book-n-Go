<?php

class Guide{
//attributs   
    private int|null $id;
    private string $nom;
    private string $prenom;   
    private string|null $numeroTel;
    private string $mail;   
    private string $mdp;   
    private string|null $cheminCertification;

//constructeur et destruteurs 
    public function __construct(?int $id = null, ?string $nom = null,?string $prenom = null, ?string $numeroTel = null,?string $mail = null, ?string $mdp = null,?string $cheminCertification = null) {
        $this->id = $id;
        $this-> nom =$nom ;
        $this->prenom = $prenom;   
        $this-> numeroTel = $numeroTel;
        $this->mail = $mail;   
        $this->mdp = $mdp;   
        $this->cheminCertification = $cheminCertification;
    }

// Encapsulation

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }


    public function getNumeroTel()
    {
        return $this->numeroTel;
    }

    public function setNumeroTel($numeroTel)
    {
        $this->numeroTel = $numeroTel;

        return $this;
    }

    public function getMail()
    {
        return $this->mail;
    }


    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }


    public function getMdp()
    {
        return $this->mdp;
    }

    public function setMdp($mdp)
    {
        $this->mdp = $mdp;

        return $this;
    }

    public function getCheminCertification()
    {
        return $this->cheminCertification;
    }


    public function setCheminCertification($cheminCertification)
    {
        $this->cheminCertification = $cheminCertification;

        return $this;
    }
    

    /**
     * Get the value of prenom
     */ 
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set the value of prenom
     *
     * @return  self
     */ 
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }
}

?>