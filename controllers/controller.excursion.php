<?php
require_once 'controller.class.php';

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
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    /**
     * @brief Récupère les visites associées aux excursions.
     * 
     * Cette méthode récupère toutes les visites via le DAO `VisiteDao` et renvoie les résultats au front-end.
     * Elle gère également les requêtes AJAX et renvoie les visites au format JSON si la requête est faite via AJAX.
     * 
     * @return void
     */
    public function getVisites()
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
    }

    public function afficherCreer()
    {
        $visites = $this->getVisites();

        echo $this->getTwig()->render('formulaire_excursion.html.twig', [
            'visites' => $visites
        ]);
    }

    public function creer(): void
    {
        // Vérifie si la requête est une requête AJAX
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        // Vérifie si le formulaire a été soumis
        if (!empty($this->getPost())) {
            $data = [
                'capacite' => $this->getPost()['capacite'] ?? '',
                'nom' => $this->getPost()['nom'] ?? '',
                'date_creation' => new DateTime(),
                'description' => $this->getPost()['description'] ?? '',
                'public' => $this->getPost()['public'] ?? 0, // 1 pour public, 0 pour privé
                'id_guide' => 1, // Guide par défaut
            ];

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
                        exit;
                    }
                    echo "Erreur: Echec du téléchargement de l'image.";
                    return;
                }
            }

            // Crée une nouvelle excursion via ExcursionDao
            $excursionDao = new ExcursionDao($this->getPdo());
            $nouvelleExcursion = $excursionDao->creer($data);

            // Si la création est réussie, gérer les visites associées
            if ($nouvelleExcursion) {
                $this->handleVisits($nouvelleExcursion->getId(), $_POST);

                if ($isAjax) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Excursion created successfully',
                        'redirect' => 'index.php?controleur=excursion&methode=lister'
                    ]);
                } else {
                    if ($isAjax) {
                        echo json_encode(['success' => false, 'message' => 'Error creating excursion']);
                    }
                }
                exit;
            } else {
                if ($isAjax) {
                    echo json_encode(['success' => false, 'message' => 'Form data is missing']);
                    exit;
                }
                echo "Erreur : Formulaire vide";
            }
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

        // Boucle sur les visites envoyées via le formulaire
        foreach ($postData as $key => $value) {
            if (strpos($key, 'temps_sur_place_') === 0) {
                // Récupère l'id de la visite à partir du nom de l'input
                $visiteId = str_replace('temps_sur_place_', '', $key);
                $tempsSurPlaceKey = 'temps_sur_place_' . $visiteId;

                // Vérifie que les données nécessaires pour la visite sont présentes
                if (!isset($postData[$tempsSurPlaceKey])) {
                    echo "Erreur: Données manquantes pour la visite ID " . $visiteId . " (temps sur place).";
                    continue;
                }

                $tempsSurPlace = $postData[$tempsSurPlaceKey]; // Temps passé sur place

                if (empty($tempsSurPlace)) {
                    echo "Erreur: Données manquantes pour la visite ID " . $visiteId . " (heure d'arrivée ou temps sur place).";
                    continue;
                }

                try {
                    $dateToday = (new DateTime())->format('Y-m-d');
                    $tempsSurPlaceObj = new DateTime($dateToday . ' ' . $tempsSurPlace);

                    // Vérifie si la visite existe
                    $visite = $visiteDao->findAllAssoc($visiteId);
                    if (!$visite) {
                        echo "Erreur : Visite introuvable pour ID " . $visiteId;
                        continue;
                    }

                    // Crée un enregistrement dans la table Composer pour associer la visite à l'excursion
                    $composer = new Composer(
                        $tempsSurPlaceObj,
                        $excursionId,
                        $visiteId
                    );

                    if (!$composerDao->creer($composer)) {
                        echo "Erreur: Échec de l'ajout de la visite ID " . $visiteId;
                    }
                } catch (Exception $e) {
                    echo "Erreur lors de l'ajout de la visite ID " . $visiteId . ": " . $e->getMessage();
                }
            }
        }
    }


    public function afficherModifier(int $id)
    {
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
        // Vérifie si la requête est une requête AJAX
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        // Vérifie si le formulaire a été soumis
        if (!empty($this->getPost())) {
            $data = [
                'capacite' => $this->getPost()['capacite'] ?? '',
                'nom' => $this->getPost()['nom'] ?? '',
                'date_creation' => new DateTime(),
                'description' => $this->getPost()['description'] ?? '',
                'public' => $this->getPost()['public'] ?? 0, // 1 pour public, 0 pour privé
                'id_guide' => 1, // Guide par défaut
            ];

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
                        exit;
                    }
                    echo "Erreur: Echec du téléchargement de l'image.";
                    return;
                }
            }

            // Crée une nouvelle excursion via ExcursionDao
            $excursionDao = new ExcursionDao($this->getPdo());
            $excursionModifiee = $excursionDao->modifier($data);

            // Si la création est réussie, gérer les visites associées
            if ($excursionModifiee) {
                $this->handleVisits($id, $_POST);

                if ($isAjax) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Excursion modifiée avec succèss',
                        'redirect' => 'index.php?controleur=excursion&methode=afficher'
                    ]);
                } else {
                    if ($isAjax) {
                        echo json_encode(['success' => false, 'message' => 'Erreur lors de la modification de l\'excursion']);
                    }
                }
                exit;
            } else {
                if ($isAjax) {
                    echo json_encode(['success' => false, 'message' => 'Information du formulaire manquantes']);
                    exit;
                }
                echo "Erreur : Formulaire vide";
            }
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
    public function supprimerAjax(int $id): void
    {
        $excursionDao = new ExcursionDao($this->getPdo());

        if ($_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            header('Content-Type: application/json');

            if ($excursionDao->supprimer($id)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Excursion deleted successfully.',
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Error occurred while deleting the excursion.',
                ]);
            }
            exit;
        }

        if ($excursionDao->supprimer($id)) {
            $this->redirect('excursion', 'lister');
        } else {
            echo "Erreur lors de la suppression de l'excursion.";
        }
    }

    public function supprimer(int $id): void
    {
        $excursionDao = new ExcursionDao($this->getPdo());

        if ($excursionDao->supprimer($id)) {
            $this->redirect('excursion', 'lister');
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

        if ($excursion) {
            echo $this->getTwig()->render('details_excursion.html.twig', [
                'excursion' => $excursion,
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
        $excursions = $excursionDao->findAll();

        echo $this->getTwig()->render('liste_excursions.html.twig', [
            'excursions' => $excursions,
        ]);
    }
}
