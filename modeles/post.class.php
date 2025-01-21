<?php
/**
 * @file post.class.php
 * @class Post
 * @brief Classe représentant un post.
 *
 * La classe `Post` représente un post avec des informations telles que l'ID, le titre, le chemin de l'image,
 * le contenu et la date de publication. Elle contient des méthodes pour accéder et modifier ces informations.
 */
class Post {
    /**
     * @var int|null
     */
    private int|null $id;
    /**
     * @var string|null
     */
    private string|null $titre;
    /**
     * @var string|null
     */
    private string|null $chemin_img;
    /**
     * @var string|null
     */
    private string|null $contenu;
    /**
     * @var int|null
     */
    private int|null $id_visite;

    /**
     * @var int|null
     */
    private int|null $id_carnet;

    /**
     * @var string|null
     */
    private string|null $date_publication; // Stocke l'ID du guide

    /**
     * @param int|null $id
     * @param string|null $titre
     * @param string|null $chemin_img
     * @param string|null $contenu
     * @param string|null $date_publication
     */
    public function __construct(?int $id = null, ?string $titre = null, ?string $chemin_img = null, ?string $contenu = null, ?string $date_publication = null) {
        $this->id = $id;
        $this->titre = $titre;
        $this->chemin_img = $chemin_img;
        $this->contenu = $contenu;
        $this->date_publication = $date_publication;
    }


    /**
     * @brief Retourne l'ID du post
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @brief Affecte l'ID du post
     * @param $id
     * @return void
     */
    public function setId($id): void
    {
        $this->id = $id;
    }


    /**
     * @brief Retourne le titre du post
     * @return string|null
     */
    public function getTitre(): ?string
    {
        return $this->titre;
    }


    /**
     * @brief Affecte le titre du post
     * @param $titre
     * @return void
     */
    public function setTitre($titre): void
    {
        $this->titre = $titre;
    }


    /**
     * @brief Retourne le chemin de l'image
     * @return string|null
     */
    public function getChemin_img(): ?string
    {
        return $this->chemin_img;
    }


    /**
     * @brief Affecte le chemin de l'image
     * @param $chemin_img
     * @return void
     */
    public function setChemin_img($chemin_img): void
    {
        $this->chemin_img = $chemin_img;
    }


    /**
     * @brief Retourne le contenu
     * @return string|null
     */
    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    /**
     * @brief Affecte le contenu
     * @param $contenu
     * @return void
     */
    public function setContenu($contenu): void
    {
        $this->contenu = $contenu;
    }

    /**
     * @brief Retourne la date de publication
     * @return DateTime|null
     */
    public function getDateHeurePublication(): ?DateTime
    {
        return $this->date_heure_publication;
    }

    /**
     * @brief Affecte la date de publication
     * @param $date_heure_publication
     * @return void
     */
    public function setDateHeurePublication($date_heure_publication): void
    {
        $this->date_heure_publication = $date_heure_publication;
    }

    /**
     * @brief Retourne l'ID du carnet
     * @return int|null
     */
    public function getIdCarnet(): ?int
    {
        return $this->id_carnet;
    }

    /**
     * @brief Affecte l'ID du carnet
     * @param int|null $id_carnet
     * @return void
     */
    public function setIdCarnet(?int $id_carnet): void
    {
        $this->id_carnet = $id_carnet;
    }

    /**
     * @brief Retourne l'ID de la visite
     * @return int|null
     */
    public function getIdVisite(): ?int
    {
        return $this->id_visite;
    }

    /**
     * @brief Affecte l'ID de la visite
     * @param int|null $id_visite
     * @return void
     */
    public function setIdVisite(?int $id_visite): void
    {
        $this->id_visite = $id_visite;
    }
}

