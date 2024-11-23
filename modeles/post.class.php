<?php

class Post {
    private int|null $id;
    private string|null $titre;
    private string|null $chemin_img;
    private string|null $contenu;
    private int|null $id_visite; // Stocke l'ID de la visite
    private int|null $id_guide; // Stocke l'ID du guide
    private int|null $id_carnet; // Stocke l'ID du guide
    private string|null $date_publication; // Stocke l'ID du guide
    /**
     * date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $date))) pourra permettre la conversion de date php en DATETIME MySQL
     */
    //public DateTime|null $date_heure_publication;
    private CarnetVoyage|null $carnetVoyage;
    private Visite|null $visite;

    public function __construct(?int $id = null, ?string $titre = null, ?string $chemin_img = null, ?string $contenu = null, ?string $date_publication = null, ?CarnetVoyage $carnetVoyage = null, ?Visite $visite = null) {
        $this->id = $id;
        $this->titre = $titre;
        $this->chemin_img = $chemin_img;
        $this->contenu = $contenu;
        $this->date_publication = $date_publication;
        $this->carnetVoyage = $carnetVoyage;
        $this->visite = $visite;
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
    public function setId($id): void
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
    public function setTitre($titre): void
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
    public function setChemin_img($chemin_img): void
    {
        $this->chemin_img = $chemin_img;
    }

    /**
     * Get the value of contenu
     */ 
    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    /**
     * Set the value of contenu
     *
     */ 
    public function setContenu($contenu): void
    {
        $this->contenu = $contenu;
    }

    /**
     * Get the value of the date of publication
     */
    public function getDateHeurePublication(): ?DateTime
    {
        return $this->date_heure_publication;
    }

    /**
     * Set the value of the date of publication
     */
    public function setDateHeurePublication($date_heure_publication): void 
    {
        $this->date_heure_publication = $date_heure_publication;
    }

    /**
     * Get the value of voyageur
     */ 
    public function getCarnetVoyage(): ?CarnetVoyage
    {
        return $this->carnetVoyage;
    }

    /**
     * Set the value of voyageur
     *
     */ 
    public function setCarnetVoyage($carnet): void
    {
        $this->carnetVoyage = $carnet;
    }

    /**
     * Get the value of visite
     */ 
    public function getVisite(): ?Visite
    {
        return $this->visite;
    }

    /**
     * Set the value of visite
     *
     */ 
    public function setVisite($visite): void
    {
        $this->visite = $visite;
    }
}