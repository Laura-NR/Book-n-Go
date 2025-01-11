<?php
require_once 'controller.class.php';
require_once 'validation/ajout_excursion.php';

/**
 * @class ControllerExcursion
 * @brief Contrôleur principal pour gérer les excursions.
 * 
 * Ce contrôleur contient des méthodes pour l'affichage des excursions, la création d'une nouvelle excursion,
 * la suppression d'une excursion, et la gestion des visites associées à chaque excursion.
 */
class ControllerExcursion extends BaseController
{
    /**
     * @brief Constructeur du contrôleur d'excursion.
     * 
     * Ce constructeur initialise les objets Twig nécessaires pour la gestion des templates.
     * 
     * @param \Twig\Environment $twig L'environnement Twig.
     * @param \Twig\Loader\FilesystemLoader $loader Le chargeur de fichiers pour Twig.
     */

     /**
     * @var Validator
     */
    private Validator $validator; // Instance de la classe Validator
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
        global $reglesValidationInsertionExcursion;
        $this->validator = new Validator($reglesValidationInsertionExcursion);
    }

    /**
     * @brief Récupère les visites associées aux excursions.
     * 
     * Cette méthode récupère toutes les visites via le DAO `VisiteDao` et renvoie les résultats au front-end.
     * Elle gère également les requêtes AJAX et renvoie les visites au format JSON si la requête est faite via AJAX.
     * 
     * @return void
     */
    public function getVisites(): array
    {
        $visiteDao = new VisiteDao($this->getPdo());
        $visites = $visiteDao->findAllAssoc();

        // Vérifie si la requête est faite via AJAX
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json; charset=utf-8');
            $jsonVisites = json_encode($visites, JSON_UNESCAPED_UNICODE);

            if ($jsonVisites === false) {
                print_r('JSON encoding failed: ' . json_last_error_msg());
            }

            echo $jsonVisites;
            exit;
        }
        return $visites;
    }


    /**
     * @brief Affiche le formulaire de création d'excursion.
     *
     * Si l'utilisateur n'est pas connecté ou n'a pas le rôle de guide, cette méthode
     * affiche un message d'erreur et met fin à l'exécution du script.
     *
     * Sinon, cette méthode affiche le formulaire de création d'excursion via le template
     * `formulaire_excursion.html.twig` en injectant les visites récupérées avec la méthode
     * `getVisites`.
     *
     * @return void
     */
    public function afficherCreer(): void
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guide') {

            if (!isset($_SESSION['messages_alertes']['auth']) || is_array($_SESSION['messages_alertes']['auth'])) {
                $_SESSION['messages_alertes'][] = ['type' => 'danger', 'message' => 'Vous n\'êtes pas autorisé à effectuer cette action.'];
            }
            $this->redirect('', '', ['excursion' => false]);
            exit;
        }

        $visites = $this->getVisites();

        echo $this->getTwig()->render('formulaire_excursion.html.twig', [
            'visites' => $visites,
        ]);
    }

    /**
     * @brief Crée une nouvelle excursion.
     *
     * Cette méthode vérifie si l'utilisateur connecté a le rôle de guide et si le formulaire a été soumis.
     * Elle valide les données du formulaire, télécharge l'image associée si présente, et utilise ExcursionDao
     * pour créer une nouvelle excursion dans la base de données. Si la création réussit, elle gère les visites
     * associées à l'excursion. En cas d'erreur, des messages d'erreur sont affichés ou renvoyés en JSON
     * pour les requêtes AJAX.
     *
     * @return void
     */
    public function creer(): void
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guide') {
            if (!isset($_SESSION['messages_alertes']['auth']) || is_array($_SESSION['messages_alertes']['auth'])) {
                $_SESSION['messages_alertes'][] = ['type' => 'danger', 'message' => 'Vous n\'êtes pas autorisé à effectuer cette action.'];
            }
            $this->redirect('', '', ['excursion' => false]);
            exit;
        }

        // Vérifie si la requête est une requête AJAX
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        // Récupère l'ID du guide connecté
        $idGuide = $_SESSION['user_id'];

        // Récupère les données du formulaire
        $donnees = array_merge($_POST, $_FILES);

        // Valider les données du formulaire
        if (!$this->validator->valider($donnees)) {
            echo json_encode([
                'success' => false,
                'errors' => $this->validator->getMessagesErreurs()
            ]);
            exit;
        }

        $data = [
            'capacite' => htmlentities($this->getPost()['capacite'] ?? '', ENT_QUOTES, 'UTF-8'),
            'nom' => htmlentities($this->getPost()['nom'] ?? '', ENT_QUOTES, 'UTF-8'),
            'date_creation' => new DateTime(),
            'description' => htmlentities($this->getPost()['description'] ?? '', ENT_QUOTES, 'UTF-8'),
            'public' => $this->getPost()['public'] ?? 0, // 1 pour public, 0 pour privé
            'id_guide' => $idGuide,
        ];

        // Valider les champs "temps_sur_place"
        $tempsSurPlaceErrors = [];
        foreach ($this->getPost() as $key => $value) {
            if (strpos($key, 'temps_sur_place_') === 0 && empty($value)) {
                $tempsSurPlaceErrors[] = "Le temps sur place pour " . substr($key, 15) . " est requis.";
            }
        }

        // Si des erreurs de validation existent, renvoyer une erreur
        if (!empty($tempsSurPlaceErrors)) {
            if ($isAjax) {
                echo json_encode(['success' => false, 'errors' => $tempsSurPlaceErrors]);
                exit;
            }
        }

        // Si un fichier image est téléchargé, l'ajouter aux données
        if (!empty($_FILES['chemin_image']['name'])) {
            $uploadDirectory = './images/';
            $fileName = basename($_FILES['chemin_image']['name']);
            $targetPath = $uploadDirectory . $fileName;

            if (move_uploaded_file($_FILES['chemin_image']['tmp_name'], $targetPath)) {
                $data['chemin_image'] = $targetPath;
            } else {
                if ($isAjax) {
                    echo json_encode(['success' => false, 'message' => 'Image upload failed']);
                }
                exit;
            }
        }

        // Crée une nouvelle excursion via ExcursionDao
        $excursionDao = new ExcursionDao($this->getPdo());
        $nouvelleExcursion = $excursionDao->creer($data);

        // Si la création est réussie, gérer les visites associées
        if ($nouvelleExcursion) {
            $this->handleVisits($nouvelleExcursion->getId(), $_POST);

            if ($isAjax) {
                $_SESSION['success_excursion'] = 'Excursion créée avec succès!';

                echo json_encode([
                    'success' => true,
                    'redirect' => 'index.php?controleur=excursion&methode=listerByGuide&id=' . $idGuide,
                ]);
            } else {
                if ($isAjax) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Une erreur est survenue lors de la création de l\'excursion.'
                    ]);
                }
            }
            exit;
        }
    }

    /**
     * @brief Gère les visites associées à une excursion.
     * 
     * Cette méthode permet d'associer des visites à une excursion via la table `Composer`.
     * Elle prend en charge les données envoyées via un formulaire dynamique et ajoute les visites correspondantes.
     * 
     * @param int $excursionId L'ID de l'excursion à laquelle les visites seront associées.
     * @param array $postData Les données du formulaire envoyées.
     * 
     * @return void
     */
    public function handleVisits(int $excursionId, array $postData): void
    {
        $composerDao = new ComposerDao($this->getPdo());
        $visiteDao = new VisiteDao($this->getPdo());

        $currentVisits = $composerDao->findByExcursion($excursionId);
        $currentVisitIds = array_column($currentVisits, 'visite_id');

        $submittedVisits = [];
        foreach ($postData as $key => $value) {
            if (strpos($key, 'temps_sur_place_') === 0) {
                $visiteId = str_replace('temps_sur_place_', '', $key);
                $tempsSurPlaceKey = 'temps_sur_place_' . $visiteId;
                if (isset($postData[$tempsSurPlaceKey])) {
                    $submittedVisits[$visiteId] = $postData[$tempsSurPlaceKey];
                }
            }
        }

        $submittedVisitIds = array_keys($submittedVisits);
        $visitsToAdd = array_diff($submittedVisitIds, $currentVisitIds);
        $visitsToRemove = array_diff($currentVisitIds, $submittedVisitIds);
        $visitsToUpdate = array_intersect($currentVisitIds, $submittedVisitIds);

        // Boucle sur les visites envoyées via le formulaire
        foreach ($visitsToAdd as $visiteId) {
            $tempsSurPlace = $submittedVisits[$visiteId];
            $dateToday = (new DateTime())->format('Y-m-d');
            $tempsSurPlaceObj = new DateTime($dateToday . ' ' . $tempsSurPlace);

            $composer = new Composer(
                $tempsSurPlaceObj,
                $excursionId,
                $visiteId
            );

            if (!$composerDao->creer($composer)) {
                echo "Erreur: Échec de l'ajout de la visite ID " . $visiteId;
            }
        }

        // Mettre à jour les visites existantes pour l'excursion
        foreach ($visitsToUpdate as $visiteId) {
            $tempsSurPlace = $submittedVisits[$visiteId];
            $dateToday = (new DateTime())->format('Y-m-d');
            $tempsSurPlaceObj = new DateTime($dateToday . ' ' . $tempsSurPlace);

            // Vérifier si la valeur actuelle de temps_sur_place est différente à la valeur qui à étée soumis dans le formulaire
            $currentTempsSurPlace = $composerDao->find($excursionId, $visiteId)->getTempsSurPlace();
            if ($currentTempsSurPlace != $tempsSurPlaceObj) {
                $composer = new Composer(
                    $tempsSurPlaceObj,
                    $excursionId,
                    $visiteId
                );

                if (!$composerDao->modifier($composer)) {
                    echo "Erreur: Échec de la mise à jour de la visite ID " . $visiteId;
                }
            }
        }

        // Enlever les visites qui ne sont plus incluses dans l'itinéraire l'excursion
        foreach ($visitsToRemove as $visiteId) {
            if (!$composerDao->supprimer($excursionId, $visiteId)) {
                echo "Erreur: Échec de la suppression de la visite ID " . $visiteId;
            }
        }
    }


    /**
     * @brief Affiche le formulaire de modification pour une excursion spécifique.
     *
     * Vérifie si l'utilisateur est connecté et a le rôle de guide avant de procéder.
     * Récupère l'excursion à modifier ainsi que les visites associées via leur ID respectif.
     * Si l'ID est invalide ou si l'excursion n'est pas trouvée, affiche un message d'erreur.
     * Utilise le template `formulaire_excursion.html.twig` pour afficher le formulaire
     * avec les données de l'excursion et les visites sélectionnées.
     *
     * @param int $id L'ID de l'excursion à modifier.
     *
     * @return void
     */
    public function afficherModifier(int $id): void
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guide') {
            if (!isset($_SESSION['erreurs_excursion']['auth']) || is_array($_SESSION['erreurs_excursion']['auth'])) {
                $_SESSION['erreurs_excursion']['auth'] = "Vous n'êtes pas autorisé à effectuer cette action.";
            }
            $this->redirect('', '', ['excursion' => false]);
            exit;
        }

        $visites = $this->getVisites();

        $excursionAmodifier = null;
        if ($id) {
            $excursionDao = new ExcursionDao($this->getPdo());
            $composerDao = new ComposerDao($this->getPdo());

            $excursionAmodifier = $excursionDao->findAssoc($id);
            $visitesSelectionnees = $composerDao->findByExcursion($id);

            if (!$excursionAmodifier) {
                echo "Erreur : Excursion introuvable pour ID $id";
                return;
            }
        } else {
            echo "Erreur : ID d'excursion non spécifié.";
            return;
        }

        echo $this->getTwig()->render('formulaire_excursion.html.twig', [
            'visites' => $visites,
            'visitesSelectionnees' => $visitesSelectionnees,
            'excursion' => $excursionAmodifier,
        ]);
    }


    /**
     * @brief Modifier les informations d'une excursion existante.
     * 
     * Cette méthode modifie les informations d'une excursion dans la base de données en fonction de son ID.
     * Si la modification échoue, un message d'erreur est affiché.
     * 
     * @param int $id L'ID de l'excursion à modifier.
     * 
     * @return void
     */
    public function modifier(int $id): void
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guide') {
            if (!isset($_SESSION['erreurs_excursion']['auth']) || is_array($_SESSION['erreurs_excursion']['auth'])) {
                $_SESSION['erreurs_excursion']['auth'] = "Vous n'êtes pas autorisé à effectuer cette action.";
            }
            $this->redirect('', '', ['excursion' => false]);
            exit;
        }

        $excursionDao = new ExcursionDao($this->getPdo());
        $currentExcursion = $excursionDao->findAssoc($id);

        if (!$currentExcursion) {
            echo "Erreur : Excursion introuvable.";
            exit;
        }

        if ($currentExcursion->getId_guide() !== $_SESSION['user_id']) {
            echo "Erreur : Vous n'êtes pas autorisé à modifier cette excursion.";
            exit;
        }

        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        $idGuide = $_SESSION['user_id'];

        if (!empty($this->getPost())) {
            $data = [
                'id' => $id,
                'capacite' => htmlentities($this->getPost()['capacite'] ?? '', ENT_QUOTES, 'UTF-8'),
                'nom' =>  htmlentities($this->getPost()['nom'] ?? '', ENT_QUOTES, 'UTF-8'),
                'date_creation' => new DateTime(),
                'description' => htmlentities($this->getPost()['description'] ?? '', ENT_QUOTES, 'UTF-8'),
                'public' => $this->getPost()['public'] ?? 0,
                'id_guide' => $idGuide,
            ];

            if (!empty($_FILES['chemin_image']['name'])) {
                $uploadDirectory = './images/';
                $fileName = basename($_FILES['chemin_image']['name']);
                $targetPath = $uploadDirectory . $fileName;

                if (move_uploaded_file($_FILES['chemin_image']['tmp_name'], $targetPath)) {
                    $data['chemin_image'] = $targetPath;
                } else {
                    if ($isAjax) {
                        echo json_encode(['success' => false, 'message' => 'Image upload failed']);
                        exit;
                    }
                    echo "Erreur: Échec du téléchargement de l'image.";
                    return;
                }
            } else {
                if ($currentExcursion && !empty($currentExcursion->getChemin_image())) {
                    $data['chemin_image'] = $currentExcursion->getChemin_image();
                }
            }

            $updated = $excursionDao->modifier($data);

            if ($updated) {
                $this->handleVisits($id, $_POST);

                if ($isAjax) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Excursion modifiée avec succès',
                        'redirect' => 'index.php?controleur=excursion&methode=afficher&id=' . $id,
                    ]);
                    exit;
                }
            } else {
                if ($isAjax) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Erreur lors de la modification de l\'excursion',
                    ]);
                    exit;
                }
                echo "Erreur : Impossible de modifier l'excursion.";
            }
        } else {
            if ($isAjax) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Données du formulaire manquantes',
                ]);
                exit;
            }
            echo "Erreur : Formulaire vide.";
        }
    }


    /**
     * @brief Supprime une excursion.
     * 
     * Cette méthode supprime une excursion de la base de données en fonction de son ID.
     * Si la suppression échoue, un message d'erreur est affiché.
     * 
     * @param int $id L'ID de l'excursion à supprimer.
     * 
     * @return void
     */

    public function supprimer(int $id): void
    {
        // session_start();

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'guide') {
            echo "Vous n'êtes pas autorisé à effectuer cette action.";
            return;
        }

        $excursionDao = new ExcursionDao($this->getPdo());
        $excursion = $excursionDao->findAssoc($id);
        if (!$excursion) {
            echo "Erreur : Excursion introuvable.";
            exit;
        }

        if ($excursion->getId_guide() !== $_SESSION['user_id']) {
            echo "Erreur : Vous n'êtes pas autorisé à supprimer cette excursion.";
            exit;
        }

        if ($excursionDao->supprimer($id)) {
            $this->redirect('excursion', 'listerByGuide', ['id' => $_SESSION['user_id']]);
        } else {
            echo "Erreur lors de la suppression de l'excursion.";
        }
    }


    /**
     * @brief Affiche les détails d'une excursion.
     * 
     * Cette méthode récupère une excursion en fonction de son ID et affiche ses détails dans le template approprié.
     * 
     * @param int $id L'ID de l'excursion à afficher.
     * 
     * @return void
     */
    public function afficher(int $id): void
    {
        $excursionDao = new ExcursionDao($this->getPdo());
        $excursion = $excursionDao->findAssoc($id);

        $composerDao = new ComposerDao($this->getPdo());
        $visites = $composerDao->findByExcursion($id);

        $engagementDao = new EngagementDao($this->getPdo());
        $engagements = $engagementDao->findEngagementsByExcursionId($id);

        $guideDao = new GuideDao($this->getPdo());

        $reservationDao = new ReservationDao($this->getPdo());
        $reservations = $reservationDao->findReservationsByExcursionId($id);

        // Format reservation dates for comparison in JavaScript
        $datesReservees = [];
        foreach ($reservations as $reservation) {
            $datesReservees[] = $reservation->getDateReservation()->format('Y-m-d');
        }

        // On utilise array_map pour ajouter l'info du guide associé (sous forme d'un objet guide) à chaque engagement, utile pour la réservation
        $engagements = array_map(
            function ($engagement) use ($guideDao) {
                $engagement->guide = $guideDao->find($engagement->getIdGuide());
                return $engagement;
            },
            $engagements
        );

        $heureDebut = null;
        if (!empty($engagements)) {
            $heureDebut = new DateTime($engagements[0]->getHeureDebut()->format('Y-m-d H:i:s'));
        }

        $heuresArrivees = [];
        if ($heureDebut) {
            foreach ($visites as $visite) {
                $heuresArrivees[$visite['visite_id']] = $heureDebut->format('H:i');
                $tempsSurPlace = $visite['temps_sur_place'];
                $parts = explode(':', $tempsSurPlace);

                if (count($parts) === 3) {
                    $heures = (int) $parts[0];
                    $minutes = (int) $parts[1];

                    $interval = new DateInterval(sprintf('PT%dH%dM', $heures, $minutes));
                    $heureDebut->add($interval);
                }
            }
        }

        if ($excursion and $_SESSION['role'] == "visiteur") {
            echo $this->getTwig()->render('details_excursion_voyageur.html.twig', [
                'excursion' => $excursion,
                'visites' => $visites,
                'engagements' => $engagements, // les engagements modifiés par l'array_map
                'datesReservees' => $datesReservees
            ]);
        } else if ($excursion and $_SESSION['role'] == "guide") {
            echo $this->getTwig()->render('details_excursion_guide.html.twig', [
                'excursion' => $excursion,
                'visites' => $visites,
            ]);
        } else if ($excursion and $_SESSION['role'] == "voyageur") {
            echo $this->getTwig()->render('details_excursion_voyageur.html.twig', [
                'excursion' => $excursion,
                'visites' => $visites,
                'engagements' => $engagements, // les engagements modifiés par l'array_map
                'datesReservees' => $datesReservees,
                'heuresArrivees' => $heuresArrivees,
            ]);
        } else {
            echo "Excursion non trouvée.";
        }
    }

    /**
     * @brief Liste toutes les excursions.
     * 
     * Cette méthode récupère toutes les excursions de la base de données et les affiche dans le template de liste d'excursions.
     * 
     * @return void
     */
    public function lister(): void
    {
        $excursionDao = new ExcursionDao($this->getPdo());
        $excursions = $excursionDao->findAllWithExistingEngagement();

        echo $this->getTwig()->render('liste_excursions.html.twig', [
            'excursions' => $excursions,
        ]);
    }


    /**
     * @brief Liste les excursions d'un guide spécifique.
     *
     * Cette méthode récupère toutes les excursions associées à un guide donné
     * en fonction de son ID. Si le paramètre 'public' est présent dans la requête
     * GET et a pour valeur 1, elle récupère uniquement les excursions publiques.
     * Sinon, elle récupère toutes les excursions du guide.
     * Les excursions sont ensuite affichées dans le template 'guide_excursions.html.twig'.
     *
     * @param int $id L'ID du guide pour lequel récupérer les excursions.
     *
     * @return void
     */
    public function listerByGuide(int $id): void
    {
        $successExcursion = isset($_SESSION['success_excursion']) ? $_SESSION['success_excursion'] : null;
        unset($_SESSION['success_excursion']);

        $excursionDao = new ExcursionDao($this->getPdo());

        $public = isset($_GET['public']) && $_GET['public'] == 1;

        if ($public) {
            $excursions = $excursionDao->findPublic($id);
        } else {
            $excursions = $excursionDao->findByGuide($id);
        }

        echo $this->getTwig()->render('guide_excursions.html.twig', [
            'success_excursion' => $successExcursion,
            'excursionsByGuide' => $excursions,
            'public' => $public,
        ]);
    }
}
