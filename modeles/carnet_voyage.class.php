<?php 

class CarnetVoyage {
    private int|null $id;
    private string|null $titre;
    private string|null $chemin_img;
    private string|null $description;
    private int|null $id_voyageur;
    //private Voyageur|null $voyageur;

    public function __construct(?int $id = null, ?string $titre = null, ?string $chemin_img = null, ?string $description = null, ?Voyageur $voyageur = null) {
        $this->id = $id;
        $this->titre = $titre;
        $this->chemin_img = $chemin_img;
        $this->description = $description;
        $this->voyageur = $voyageur;
    }

    /**
     * Get the value of id
     */ 
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     */ 
    public function setId(?int $id):void
    {
        $this->id = $id;
    }

    /**
     * Get the value of titre
     */
    public function getTitre(): ?string
    {
        return $this->titre;
    }

    /**
     * Set the value of titre
     *
     */ 
    public function setTitre(?string $titre):void
    {
        $this->titre = $titre;
    }

    /**
     * Get the value of chemin_img
     */
    public function getChemin_img(): ?string
    {
        return $this->chemin_img;
    }

    /**
     * Set the value of chemin_img
     *
     */ 
    public function setChemin_img(?string $chemin_img):void
    {
        $this->chemin_img = $chemin_img;
    }

    /**
     * Get the value of description
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     */ 
    public function setDescription(?string $description):void
    {
        $this->description = $description;
    }

    /**
     * Get the value of voyageur
     */
    public function getVoyageur(): ?Voyageur
    {
        return $this->voyageur;
    }

    /**
     * Set the value of voyageur
     *
     */ 
    public function setVoyageur(?Voyageur $voyageur):void
    {
        $this->voyageur = $voyageur;
    }

    public function getIdVoyageur(): ?int
    {
        return $this->id_voyageur;
    }

    public function setIdVoyageur(?int $id_voyageur): void
    {
        $this->id_voyageur = $id_voyageur;
    }
    
}