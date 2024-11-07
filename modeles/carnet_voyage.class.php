<?php 

class CarnetVoyage {
    private int|null $id;
    private string|null $titre;
    private string|null $chemin_img;
    private string|null $description;
    private Voyageur $voyageur;

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
    public function setTitre(?int $titre):void
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
    public function setChemin_img(?int $chemin_img):void
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
    public function setDescription(?int $description):void
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
    
}