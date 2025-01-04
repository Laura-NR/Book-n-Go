<?php
class Reservation
{
    // Déclaration des propriétés
    private int|null $id;
    private int|null $idVoyageur;
    private DateTime|null $dateReservation;
    private int|null $idEngagement;

    // Constructeur de la classe
    public function __construct(?int $id, ?int $idVoyageur, ?DateTime $dateReservation, ?int $idEngagement)
    {
        $this->id = $id;
        $this->idVoyageur = $idVoyageur;
        $this->dateReservation = $dateReservation;
        $this->idEngagement = $idEngagement;
    }


    // Getter pour l'id
    public function getId()
    {
        return $this->id;
    }

    // Setter pour l'id
    public function setId($id)
    {
        $this->id = $id;
    }

    // Getter pour l'idVoyageur
    public function getIdVoyageur()
    {
        return $this->idVoyageur;
    }

    // Setter pour l'idVoyageur
    public function setIdVoyageur($idVoyageur)
    {
        $this->idVoyageur = $idVoyageur;
    }

    // Getter pour la dateReservation
    public function getDateReservation() : ?DateTime
    {
        return $this->dateReservation;
    }

    // Setter pour la dateReservation
    public function setDateReservation(?DateTime $dateReservation)
    {
        $this->dateReservation = $dateReservation;
    }

    // Getter pour l'idEngagement
    public function getIdEngagement()
    {
        return $this->idEngagement;
    }

    // Setter pour l'idEngagement
    public function setIdEngagement($idEngagement)
    {
        $this->idEngagement = $idEngagement;
    }

    // Méthode pour afficher les détails de la réservation
    public function afficherDetails()
    {
        echo "ID de la réservation: " . $this->getId() . "<br>";
        echo "ID du voyageur: " . $this->getIdVoyageur() . "<br>";
        echo "Date de la réservation: " . $this->getDateReservation() . "<br>";
        echo "ID de l'engagement: " . $this->getIdEngagement() . "<br>";
    }
}


?>
