<?php
require_once 'controller.class.php';
require_once 'include.php';

/**
 * @file controller.reservation.php
 * @class ControllerReservation
 * @brief Classe du contrôleur pour la gestion des réservations
 */
class ControllerReservation extends BaseController {
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
    }
    /**
     * @brief Affiche la page du planning des réservations d'un voyageur
     * @throws Exception si le paramètre "id" n'est pas renseigné
     * @return void
     */
    public function afficherPlanning(): void
    {
        // Vérification si l'utilisateur est connecté et a un rôle défini
        if (!isset($_SESSION['role'])) {
            throw new Exception("Accès refusé : rôle utilisateur inconnu");
        }

        // Debug : Afficher le rôle de l'utilisateur
        var_dump("Rôle de l'utilisateur :", $_SESSION['role']);

        if ($_SESSION['role'] === "voyageur") {
            $id_voyageur = $_GET['id'] ?? null;
            if (!$id_voyageur) {
                throw new Exception("Paramètre manquant : id_voyageur");
            }

            // Debug : Afficher l'ID du voyageur
            var_dump("ID du voyageur :", $id_voyageur);

            $reservationDAO = new ReservationDAO($this->getPdo());
            $reservations = $reservationDAO->getReservationsByVoyageur((int)$id_voyageur);

            // Debug : Afficher les réservations récupérées
            var_dump("Réservations récupérées :", $reservations);

            // Récupérer les engagements associés aux réservations
            $engagementDAO = new EngagementDAO($this->getPdo());
            $engagements = [];
            foreach ($reservations as $reservation) {
                // Debug : Afficher chaque réservation avant de récupérer l'engagement
                var_dump("Réservation en cours de traitement :", $reservation);

                if (isset($reservation['id_engagement'])) {
                    $engagement = $engagementDAO->getEngagementById2($reservation['id_engagement']);
                    if ($engagement) {
                        $engagements[] = $engagement;
                    } else {
                        // Debug : Avertissement si l'engagement n'est pas trouvé
                        var_dump("Avertissement : Engagement non trouvé pour id_engagement = " . $reservation['id_engagement']);
                    }
                } else {
                    // Debug : Avertissement si id_engagement est manquant
                    var_dump("Avertissement : id_engagement manquant dans la réservation");
                }
            }

            // Debug : Afficher les engagements récupérés
            var_dump("Engagements récupérés :", $engagements);

            // Log des données pour débogage
            error_log(print_r($reservations, true));
            error_log(print_r($engagements, true));

            echo $this->getTwig()->render('planning_template.html.twig', [
                'reservations' => $reservations,
                'engagements' => $engagements,
            ]);
        }
        elseif ($_SESSION['role'] === "guide") {
            $id_guide = $_GET['id'] ?? null;
            if (!$id_guide) {
                throw new Exception("Paramètre manquant : id_guide");
            }

            // Debug : Afficher l'ID du guide
            var_dump("ID du guide :", $id_guide);

            $engagementDAO = new EngagementDAO($this->getPdo());
            $engagements = $engagementDAO->getEngagementById((int)$id_guide);

            // Debug : Afficher les engagements récupérés
            var_dump("Engagements récupérés pour le guide :", $engagements);

            // Log des données pour débogage
            error_log(print_r($engagements, true));

            echo $this->getTwig()->render('planning_template.html.twig', [
                'engagements' => $engagements,
            ]);
        }
        else {
            throw new Exception("Accès refusé : rôle inconnu");
        }
    }
    /**
     * @brief Crée une nouvelle réservation pour un voyageur
     *
     * Crée une nouvelle réservation pour un voyageur en fonction des données
     * soumises (id de l'excursion, id du voyageur, id de l'engagement, date de
     * réservation).
     *
     * Si la création réussit, redirige vers la page d'affichage de l'excursion
     * avec l'ID de l'excursion.
     *
     * @throws Exception si le paramètre "id_excursion" n'est pas renseigné
     * @throws Exception si le paramètre "id_voyageur" n'est pas renseigné
     * @throws Exception si le paramètre "id_engagement" n'est pas renseigné
     * @throws Exception si le paramètre "date_reservation" n'est pas renseigné
     *
     * @return void
     */
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
    /**
     * @brief Supprime une réservation
     *
     * Supprime une réservation en fonction de son ID.
     *
     * Si la suppression réussit, redirige vers la page de la liste des réservations.
     *
     * @throws Exception si le paramètre "id_reservation" n'est pas renseigné
     *
     * @return void
     */
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