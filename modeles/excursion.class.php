<?php
/**
 * @file Excursion.php
 * @brief Classe représentant une excursion.
 *
 * La classe `Excursion` représente une excursion avec des informations comme l'ID, la capacité, le nom,
 * la date de visite, la description, le chemin de l'image associée, l'état public et l'ID du guide.
 * Elle contient des méthodes pour accéder et modifier ces informations.
 *
 * @class Excursion
 * @brief Représente une excursion.
 */
class Excursion
{
    private ?int $id; ///< ID de l'excursion.
    private ?int $capacite; ///< Capacité de l'excursion (nombre de participants).
    private ?string $nom; ///< Nom de l'excursion.
    private ?DateTime $date_visite; ///< Date et heure de la visite.
    private ?string $description; ///< Description de l'excursion.
    private ?string $chemin_image; ///< Chemin de l'image associée à l'excursion.
    private ?bool $public; ///< Indique si l'excursion est publique ou non.
    private ?int $id_guide; ///< ID du guide de l'excursion.

    /**
     * Constructeur de la classe Excursion.
     *
     * @param int|null $id L'ID de l'excursion.
     * @param int|null $capacite La capacité de l'excursion.
     * @param string|null $nom Le nom de l'excursion.
     * @param DateTime|null $date_visite La date et l'heure de la visite.
     * @param string|null $description La description de l'excursion.
     * @param string|null $chemin_image Le chemin de l'image de l'excursion.
     * @param bool|null $public L'état de visibilité publique de l'excursion.
     * @param int|null $id_guide L'ID du guide de l'excursion.
     */
    public function __construct(
        ?int $id = null,
        ?int $capacite = null,
        ?string $nom = null,
        ?DateTime $date_visite = null,
        ?string $description = null,
        ?string $chemin_image = null,
        ?bool $public = null,
        ?int $id_guide = null
    ) {
        $this->id = $id;
        $this->capacite = $capacite;
        $this->nom = $nom;
        $this->date_visite = $date_visite;
        $this->description = $description;
        $this->chemin_image = $chemin_image;
        $this->public = $public;
        $this->id_guide = $id_guide;
    }

    // Getters et setters (encapsulation) pour les propriétés de la classe

    /**
     * Obtient l'ID de l'excursion.
     * 
     * @return int|null L'ID de l'excursion.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Définit l'ID de l'excursion.
     *
     * @param int $id L'ID de l'excursion.
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * Obtient la capacité de l'excursion.
     * 
     * @return int|null La capacité de l'excursion.
     */
    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    /**
     * Définit la capacité de l'excursion.
     *
     * @param int $capacite La capacité de l'excursion.
     */
    public function setCapacite($capacite): void
    {
        $this->capacite = $capacite;
    }

    /**
     * Obtient le nom de l'excursion.
     * 
     * @return string|null Le nom de l'excursion.
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * Définit le nom de l'excursion.
     *
     * @param string $nom Le nom de l'excursion.
     */
    public function setNom($nom): void
    {
        $this->nom = $nom;
    }

    /**
     * Obtient la date et l'heure de la visite.
     * 
     * @return DateTime|null La date et l'heure de la visite.
     */
    public function getDate_visite(): ?DateTime
    {
        return $this->date_visite;
    }

    /**
     * Définit la date et l'heure de la visite.
     *
     * @param DateTime|null $date_visite La date et l'heure de la visite.
     */
    public function setDate_visite(?DateTime $date_visite): void
    {
        $this->date_visite = $date_visite;
    }

    /**
     * Obtient la description de l'excursion.
     * 
     * @return string|null La description de l'excursion.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Définit la description de l'excursion.
     *
     * @param string $description La description de l'excursion.
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * Obtient le chemin de l'image associée à l'excursion.
     * 
     * @return string|null Le chemin de l'image.
     */
    public function getChemin_image(): ?string
    {
        return $this->chemin_image;
    }

    /**
     * Définit le chemin de l'image associée à l'excursion.
     *
     * @param string $chemin_image Le chemin de l'image.
     */
    public function setChemin_image($chemin_image): void
    {
        $this->chemin_image = $chemin_image;
    }

    /**
     * Obtient l'état de l'excursion (publique ou non).
     * 
     * @return bool|null L'état public de l'excursion.
     */
    public function getPublic(): ?bool
    {
        return $this->public;
    }

    /**
     * Définit l'état de l'excursion (publique ou non).
     *
     * @param bool $public L'état public de l'excursion.
     */
    public function setPublic($public): void
    {
        $this->public = $public;
    }

    /**
     * Obtient l'ID du guide de l'excursion.
     * 
     * @return int|null L'ID du guide.
     */
    public function getId_guide(): ?int
    {
        return $this->id_guide;
    }

    /**
     * Définit l'ID du guide de l'excursion.
     *
     * @param int $id_guide L'ID du guide.
     */
    public function setId_guide($id_guide): void
    {
        $this->id_guide = $id_guide;
    }
}
