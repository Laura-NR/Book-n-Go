<?php
require_once 'controller.class.php';
require_once 'include.php';

class ControllerReservation extends BaseController {
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
    }
    function afficherPlanning($id_voyageur= 31)
    {
        // Instanciation du DAO
        $reservationDAO = new ReservationDAO($this->getPdo());

        // Récupérer les réservations avec deux arguments
        $reservations = $reservationDAO->getReservationsByVoyageur($id_voyageur);

        // Charger la vue
        echo $this->getTwig()->render('planning_template.html.twig', [
            'reservations' => $reservations
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

}
?>