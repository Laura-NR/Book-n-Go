<?php
require_once 'controller.class.php';
require_once 'include.php';

class ControllerReservation extends BaseController {
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
    }
    function afficherPlanning($id_voyageur= 1)
    {
        // Supposons que le deuxième paramètre soit une date, vous pouvez le définir ainsi
        $date = date('Y-m-d');  // Exemple de date actuelle

        // Instanciation du DAO
        $reservationDAO = new ReservationDAO($this->getPdo());

        // Récupérer les réservations avec deux arguments
        $reservations = $reservationDAO->getReservationsByVoyageur($id_voyageur);

        // Charger la vue
        echo $this->getTwig()->render('planning_template.html.twig', [
            'reservations' => $reservations
        ]);
    }


}
?>