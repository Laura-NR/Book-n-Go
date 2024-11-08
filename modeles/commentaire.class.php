<?php

class Commentaire{
    private ?int $id;
    private ?date $date_heure_publication;
    private ?string $contenu;
    private ?int $id_voyageur;
    private ?int $id_post;

    public function __construct(?int $id = null, ?date $date_heure = null, ?string $contenu = null, ?int $id_post = null, ?int $id_voyageur = null){
        $this->id = $id;
        $this->date_heure_publication = $date_heure;
        $this->contenu = $contenu;
        $this->id_post = $id_post;
        $this->id_voyageur = $id_voyageur;
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
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * Get the value of date_heure_publication
     */
    public function getDateHeurePublication(): ?date
    {
        return $this->date_heure_publication;
    }

    /**
     * Set the value of date_heure_publication
     */
    public function setDateHeurePublication(?date $date_heure_publication): void
    {
        $this->date_heure_publication = $date_heure_publication;
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
     */
    public function setContenu(?string $contenu): void
    {
        $this->contenu = $contenu;

    }

    /**
     * Get the value of id_voyageur
     */
    public function getIdVoyageur(): ?int
    {
        return $this->id_voyageur;
    }

    /**
     * Set the value of id_voyageur
     */
    public function setIdVoyageur(?int $id_voyageur): void
    {
        $this->id_voyageur = $id_voyageur;
    }

    /**
     * Get the value of id_post
     */
    public function getIdPost(): ?int
    {
        return $this->id_post;
    }

    /**
     * Set the value of id_post
     */
    public function setIdPost(?int $id_post): void
    {
        $this->id_post = $id_post;
    }
}