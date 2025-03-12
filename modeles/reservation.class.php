<?php
/**
 * @file reservation.class.php
 * @class Reservation
 * @brief Classe pour les réservations
 *
 * Cette classe permet de gérer les réservations. Elle contient les informations de
 * base d'une réservation : l'ID, l'ID du voyageur, la date de réservation, l'ID de
 * l'engagement.
 *
 */
class Reservation
{

    /**
     * @var int|null
     */
    private int|null $id;
    /**
     * @var int|null
     */
    private int|null $idVoyageur;
    /**
     * @var DateTime|null
     */
    private DateTime|null $dateReservation;
    /**
     * @var int|null
     */
    private int|null $idEngagement;

    // Constructeur de la classe

    /**
     * @param int|null $id
     * @param int|null $idVoyageur
     * @param DateTime|null $dateReservation
     * @param int|null $idEngagement
     */
    public function __construct(?int $id, ?int $idVoyageur, ?DateTime $dateReservation, ?int $idEngagement)
    {
        $this->id = $id;
        $this->idVoyageur = $idVoyageur;
        $this->dateReservation = $dateReservation;
        $this->idEngagement = $idEngagement;
    }


    /**
     * @brief Retourne l'ID de la reservation
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @brief Affecte l'ID de la reservation
     * @param $id
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @brief Retourne l'ID du voyageur
     * @return int|null
     */
    public function getIdVoyageur()
    {
        return $this->idVoyageur;
    }

    /**
     * @brief Affecte l'ID du voyageur
     * @param $idVoyageur
     * @return void
     */
    public function setIdVoyageur($idVoyageur)
    {
        $this->idVoyageur = $idVoyageur;
    }

    /**
     * @brief Retourne la date de la reservation
     * @return DateTime|null
     */
    public function getDateReservation() : ?DateTime
    {
        return $this->dateReservation;
    }

    /**
     * @brief Affecte la date de la reservation
     * @param DateTime|null $dateReservation
     * @return void
     */
    public function setDateReservation(?DateTime $dateReservation)
    {
        $this->dateReservation = $dateReservation;
    }

    /**
     * @brief Retourne l'ID de l'engagement
     * @return int|null
     */
    public function getIdEngagement()
    {
        return $this->idEngagement;
    }

    /**
     * @brief Affecte l'ID de l'engagement
     * @param $idEngagement
     * @return void
     */
    public function setIdEngagement($idEngagement)
    {
        $this->idEngagement = $idEngagement;
    }

    /**
     * @brief Affiche les details de la reservation
     * @return void
     */
    public function afficherDetails()
    {
        echo "ID de la réservation: " . $this->getId() . "<br>";
        echo "ID du voyageur: " . $this->getIdVoyageur() . "<br>";
        echo "Date de la réservation: " . $this->getDateReservation() . "<br>";
        echo "ID de l'engagement: " . $this->getIdEngagement() . "<br>";
    }
}
?>
