<?php

class Post {
    private int|null $id;
    private string|null $titre;
    private string|null $chemin_img;
    private string|null $contenu;
    private int|null $id_visite;

    // Stocke l'ID de la visite
    private int|null $id_carnet;

     // Stocke l'ID du guide
    private string|null $date_publication; // Stocke l'ID du guide
    /**
     * date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $date))) pourra permettre la conversion de date php en DATETIME MySQL
     */

    public function __construct(?int $id = null, ?string $titre = null, ?string $chemin_img = null, ?string $contenu = null, ?string $date_publication = null) {
        $this->id = $id;
        $this->titre = $titre;
        $this->chemin_img = $chemin_img;
        $this->contenu = $contenu;
        $this->date_publication = $date_publication;
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

    public function getIdCarnet(): ?int
    {
        return $this->id_carnet;
    }

    public function setIdCarnet(?int $id_carnet): void
    {
        $this->id_carnet = $id_carnet;
    }

    public function getIdVisite(): ?int
    {
        return $this->id_visite;
    }

    public function setIdVisite(?int $id_visite): void
    {
        $this->id_visite = $id_visite;
    }
}

