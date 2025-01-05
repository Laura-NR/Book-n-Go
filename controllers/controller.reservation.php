<?php
require_once 'controller.class.php';
require_once 'include.php';

class ControllerReservation extends BaseController {
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
    }
    public function afficherPlanning(): void
    {
        $id_voyageur = $_GET['id'] ?? null;

        if (!$id_voyageur) {
            throw new Exception("Paramètre manquant : id_voyageur");
        }

        $reservationDAO = new ReservationDAO($this->getPdo());
        $reservations = $reservationDAO->getReservationsByVoyageur((int)$id_voyageur);

        echo $this->getTwig()->render('planning_template.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    public function creer(): void
    {
        $idExcursion = $_POST['id_excursion'];
        $idVoyageur = $_POST['id_voyageur'];
        $idEngagement = $_POST['id_engagement'];
        $dateReservationString = $_POST['date_reservation'];
        $dateReservation = new DateTime($dateReservationString);

        $reservation = new Reservation(null, $idVoyageur, $dateReservation, $idEngagement);
        $reservationDao = new ReservationDao($this->getPdo());
        $succes = $reservationDao->inserer($reservation);

        if ($succes) {
            $this->redirect('excursion', 'afficher', ['id' => $idExcursion]); // A CHANGER POUR UN RETOUR SUR
        } else {
            echo "Erreur lors de la création de la réservation.";
        }
    }
    public function supprimerReservation(): void
    {
        // Récupérer l'ID de la réservation depuis l'URL ou le formulaire
        $id_reservation = $_GET['id'] ?? null;

        if (!$id_reservation) {
            throw new Exception("Paramètre manquant : id_reservation");
        }

        // Instanciation du DAO
        $reservationDAO = new ReservationDAO($this->getPdo());

        // Appel de la méthode de suppression
        $reservationDAO->supprimerReservation((int)$id_reservation);

        // Redirection après la suppression (vers la liste ou autre page)
        header("Location: index.php?controleur=reservation&methode=lister");
        exit;
    }

}
?>