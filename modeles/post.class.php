<?php

class Post {
    private int|null $id;
    private string|null $titre;
    private string|null $chemin_img;
    private string|null $contenu;
    public Date|null $date_heure_publication;
    private Voyageur $voyageur;
    private PointItineraire $point;

    public function __construct(?int $id = null, ?string $titre = null, ?string $chemin_img = null, ?string $contenu = null, ?Date $date_heure_publication = null, ?Voyageur $voyageur = null, ?PointItineraire $point = null) {
        $this->id = $id;
        $this->titre = $titre;
        $this->chemin_img = $chemin_img;
        $this->contenu = $contenu;
        $this->date_heure_publication = $date_heure_publication;
        $this->voyageur = $voyageur;
        $this->point = $point;
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
    public function getDateHeurePublication(): ?Date
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
    public function getVoyageur(): ?Voyageur
    {
        return $this->voyageur;
    }

    /**
     * Set the value of voyageur
     *
     */ 
    public function setVoyageur($voyageur): void
    {
        $this->voyageur = $voyageur;
    }

    /**
     * Get the value of point
     */ 
    public function getPoint(): ?PointItineraire
    {
        return $this->point;
    }

    /**
     * Set the value of point
     *
     */ 
    public function setPoint($point): void
    {
        $this->point = $point;
    }
}