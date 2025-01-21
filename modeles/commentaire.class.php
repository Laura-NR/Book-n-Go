<?php
/**
 * @file commentaire.class.php
 * @brief Classe représentant un commentaire.
 *
 * La classe `Commentaire` représente un commentaire avec des informations comme l'ID, la date et heure de publication,
 * le contenu, l'ID du post et l'ID du voyageur.
 * Elle contient des méthodes pour accéder et modifier ces informations.
 *
 * @class Commentaire
 * @brief Représente un commentaire.
 */
class Commentaire{
    /**
     * @var int|null
     */
    private ?int $id;
    /**
     * @var DateTime|null
     */
    private ?DateTime $date_heure_publication;
    /**
     * @var string|null
     */
    private ?string $contenu;
    /**
     * @var int|null
     */
    private ?int $id_voyageur;
    /**
     * @var int|null
     */
    private ?int $id_post;

    /**
     * @param int|null $id
     * @param DateTime|null $date_heure
     * @param string|null $contenu
     * @param int|null $id_post
     * @param int|null $id_voyageur
     */
    public function __construct(?int $id = null, ?DateTime $date_heure = null, ?string $contenu = null, ?int $id_post = null, ?int $id_voyageur = null){
        $this->id = $id;
        $this->date_heure_publication = $date_heure;
        $this->contenu = $contenu;
        $this->id_post = $id_post;
        $this->id_voyageur = $id_voyageur;
    }

    /**
     * @return int|null -> id du commentaire
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @brief Affecte un id à un commentaire
     * @param int|null -> id a affecter
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return DateTime|null -> date et heure de publication
     */
    public function getDateHeurePublication(): ?DateTime
    {
        return $this->date_heure_publication;
    }

    /**
     * @brief Affecte une date et heure de publication à un commentaire
     * @param DateTime|null -> date et heure de publication
     */
    public function setDateHeurePublication(?DateTime $date_heure_publication): void
    {
        $this->date_heure_publication = $date_heure_publication;
    }

    /**
     * @brief Recupere le contenu du commentaire
     * @return string|null -> contenu du commentaire
     */
    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    /**
     * @brief Affecte un contenu à un commentaire
     * @param string|null -> contenu a affecter
     */
    public function setContenu(?string $contenu): void
    {
        $this->contenu = $contenu;

    }

    /**
     * @brief Recupere l'id du voyageur qui a commenté
     * @return int|null -> id du voyageur
     */
    public function getIdVoyageur(): ?int
    {
        return $this->id_voyageur;
    }

    /**
     * @brief Affecte un id de voyageur au post
     * @param int|null -> id du voyageur
     */
    public function setIdVoyageur(?int $id_voyageur): void
    {
        $this->id_voyageur = $id_voyageur;
    }

    /**
     * @brief Retourne l'id du post
     * @return int|null -> id du post
     */
    public function getIdPost(): ?int
    {
        return $this->id_post;
    }

    /**
     * @brief Affecte un id de post au commentaire
     * @param int|null -> id du post
     */
    public function setIdPost(?int $id_post): void
    {
        $this->id_post = $id_post;
    }
}