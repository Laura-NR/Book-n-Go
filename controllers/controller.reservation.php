<?php
require_once 'controller.class.php';
require_once 'include.php';

class ControllerReservation extends BaseController {
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
    }

    public function creer(): void
    {
        $idExcursion = $_POST['id_excursion'];
        $idVoyageur = $_POST['id_voyageur'];
        $idEngagement = $_POST['id_engagement'];
        $dateReservationString = $_POST['date_reservation'];
        $dateReservation = new DateTime($dateReservationString);
//        var_dump($_POST);
//        var_dump($dateReservation);

        $reservation = new Reservation(null, $idVoyageur, $dateReservation, $idEngagement);
        $reservationDao = new ReservationDao($this->getPdo());
        $success = $reservationDao->inserer($reservation);

        if ($success) {
            $this->redirect('excursion', 'afficher', ['id' => $idExcursion]); // A CHANGER POUR UN RETOUR SUR
        } else {
            echo "Erreur lors de la création de la réservation.";
        }
    }

}
?>