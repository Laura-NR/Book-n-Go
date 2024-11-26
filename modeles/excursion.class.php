<?php

class Excursion
{
    private ?int $id;
    private ?int $capacite ;
    private ?string $nom;
    private ?DateTime $date_visite;
    private ?string $description;
    private ?string $chemin_image;
    private ?bool $public ;
    private ?int $id_guide;

    // creer constructeur de visite
    public function __construct(
        ?int $id= null,
        ?int $capacite = null,
        ?string $nom= null,
        ?DateTime $date_visite= null,
        ?string $description= null,
        ?string $chemin_image= null,
        ?bool $public= null,
        ?int $id_guide= null)
    {

        $this->id = $id;
        $this->capacite = $capacite;
        $this->nom = $nom;
        $this->date_visite = $date_visite;
        $this->description = $description;
        $this->chemin_image = $chemin_image;
        $this->public = $public;
        $this->id_guide = $id_guide;

    }

    //ENCAPSULTATION GETTER ET SETTER
    //variable id 

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;

    }

    //variable capacite

     public function getCapacite() : ?int
    {
        return $this->capacite;
    }

    public function setCapacite($capacite): void
    {
        $this->capacite = $capacite;
    }

    //variable nom

    public function getNom() : ?string
    {
        return $this->nom;
    }

    public function setNom($nom):void
    {
        $this->nom = $nom;

    }

    //variable date_visite

    public function getDate_visite() : ?DateTime
    {
        return $this->date_visite;
    }

    public function setDate_visite(?DateTime $date_visite):void
    {
        $this->date_visite = $date_visite;

    }

    //variable description
    public function getDescription() : ?string
    {
        return $this->description;
    }


    public function setDescription($description):void
    {
        $this->description = $description;


    }

    //variable chemin_image
    
    public function getChemin_image() : ?string
    {
        return $this->chemin_image;
    }

    public function setChemin_image($chemin_image):void
    {
        $this->chemin_image = $chemin_image;


    }

    //variable public

    public function getPublic() : ?bool
    {
        return $this->public;
    }

 
    public function setPublic($public):void
    {
        $this->public = $public;

    }

    //variable id_guide

    public function getId_guide() : ?int
    {
        return $this->id_guide;
    }
 
    public function setId_guide($id_guide):void
    {
        $this->id_guide = $id_guide;


    }
}