<?php 
/**
 * @file engagement.class.php
 * @brief Classe représentant un engagement.
 *
 * La classe `Engagement` représente un engagement avec des informations comme l'ID, la date de debut de disponibilité,
 * la date de fin de disponibilité, l'ID de l'excursion et l'ID du guide.
 * Elle contient des méthodes pour accéder et modifier ces informations.
 *
 * @class Engagement
 * @brief Représente un engagement.
 */
class Engagement {
    /**
     * @var int|null
     */
    private int|null $id;
    /**
     * @var DateTime|null
     */
    private DateTime|null $date_debut_dispo; //a changer pour dateDebutDispo (formatage camelcase)
    /**
     * @var DateTime|null
     */
    private DateTime|null $date_fin_dispo; // de même
    /**
     * @var int|null
     */
    private int|null $id_excursion;
    /**
     * @var int|null
     */
    private int|null $id_guide;
    /**
     * @var DateTime|null
     */
    private DateTime|null $heure_debut;

    // /!\ ATTENTION : dû aux cahngements encore incomplets les attributs et leurs getters ne possèdent pas le même nommage,
    // et donc pour acceder aux attributs depuis Twig, on utilisera par exemple XXXX.dateDebutDispo et non XXXX.date_debut_dispo

    /**
     * @param int|null $id
     * @param DateTime|null $date_debut_dispo
     * @param DateTime|null $date_fin_dispo
     * @param int|null $id_excursion
     * @param int|null $id_guide
     * @param DateTime|null $heure_debut
     */
    public function __construct(?int $id = null, ?DateTime $date_debut_dispo = null, ?DateTime $date_fin_dispo = null, ?int $id_excursion = null, ?int $id_guide = null, ?DateTime $heure_debut = null) {
        $this->id = $id;
        $this->date_debut_dispo = $date_debut_dispo;
        $this->date_fin_dispo = $date_fin_dispo;
        $this->id_excursion = $id_excursion;
        $this->id_guide = $id_guide;
        $this->heure_debut = $heure_debut;
    }


    /**
     * @brief Retourne l'id de l'engagement
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @brief Affecte l'id de l'engagement
     * @param $id
     * @return void
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @brief Retourne la date de debut de disponibilité
     * @return DateTime|null
     */
    public function getDateDebutDispo(): ?DateTime
    {
        return $this->date_debut_dispo;
    }

    /**
     * @brief Affecte la date de debut de disponibilité
     * @param DateTime|null $date_debut_dispo
     * @return void
     */
    public function setDateDebutDispo(?DateTime $date_debut_dispo): void
    {
        $this->date_debut_dispo = $date_debut_dispo;
    }

    /**
     * @brief Retourne la date de fin de disponibilité
     * @return DateTime|null
     */
    public function getDateFinDispo(): ?DateTime
    {
        return $this->date_fin_dispo;
    }

    /**
     * @brief Affecte la date de fin de disponibilité
     * @param DateTime|null $date_fin_dispo
     * @return void
     */
    public function setDateFinDispo(?DateTime $date_fin_dispo): void
    {
        $this->date_fin_dispo = $date_fin_dispo;
    }

    /**
     * @brief Retourne l'id de l'excursion
     * @return int|null
     */
    public function getExcursion(): ?int
    {
        return $this->id_excursion;
    }

    /**
     * @brief Affecte l'id de l'excursion
     * @param int|null $id_excursion
     * @return void
     */
    public function setExcursion(?int $id_excursion): void
    {
        $this->id_excursion = $id_excursion;
    }

    /**
     * @brief Retourne l'id du guide
     * @return int|null
     */
    public function getIdGuide(): ?int
    {
        return $this->id_guide;
    }

    /**
     * @brief Affecte l'id du guide
     * @param int|null $id_guide
     * @return void
     */
    public function setGuide(?int $id_guide): void
    {
        $this->id_guide = $id_guide;
    }

    /**
     * @brief Retourne l'heure de debut
     * @return DateTime|null
     */
    public function getHeureDebut(): ?DateTime
    {
        return $this->heure_debut;
    }

    /**
     * @brief Affecte l'heure de debut
     * @param DateTime|null $heure_debut
     * @return void
     */
    public function setHeureDebut(?DateTime $heure_debut): void
    {
        $this->heure_debut = $heure_debut;
    }

    /**
     * @brief Retourne l'id de l'excursion
     * @return int|null
     */
    public function getIdExcursion(): ?int
    {
        return $this->id_excursion;
    }

    /**
     * @brief Affecte l'id de l'excursion
     * @param int|null $id_excursion
     * @return void
     */
    public function setIdExcursion(?int $id_excursion): void
    {
        $this->id_excursion = $id_excursion;
    }

//    public function getIdGuide(): ?int
//    {
//        return $this->id_guide;
//    }
//
//    public function setIdGuide(?int $id_guide): void
//    {
//        $this->id_guide = $id_guide;
//    }

}