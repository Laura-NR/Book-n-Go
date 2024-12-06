<?php
require_once 'controller.class.php';

class ControllerExcursion extends BaseController
{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    public function recupererVisites()
    {
        $visiteDao = new VisiteDao($this->getPdo());
        $visites = $visiteDao->findAllAssoc();

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json; charset=utf-8');


            $jsonVisites = json_encode($visites, JSON_UNESCAPED_UNICODE);
            if ($jsonVisites === false) {
                print_r('JSON encoding failed: ' . json_last_error_msg());
            }

            echo $jsonVisites;
            exit;
        }

        echo $this->getTwig()->render('creation_excursion.html.twig', [
            'visites' => $visites
        ]);
    }

    // Affiche le formulaire de création d'excursion et enregistre les données si envoyées
    public function creer(): void
    {
        //Vérifier si la requête est une requête ajax
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
        }

        // Vérifie si le formulaire a été soumis
        if (!empty($this->getPost())) {
            $data = [
                'capacite' => $this->getPost()['capacite'] ?? '',
                'nom' => $this->getPost()['nom'] ?? '',
                'date_visite' => new DateTime(),
                'description' => $this->getPost()['description'] ?? '',
                'public' => $this->getPost()['public'] ?? 0, // 1 pour public, 0 pour privé
                'id_guide' => 1,
            ];

            /* $id_guide = $_SESSION['id_guide'] ?? null;
            if ($id_guide) {
                $data['id_guide'] = $id_guide;
            } else {
                echo "Erreur : L'identifiant du guide est manquant.";
                return;
            } */

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

            // Utilisation de ExcursionDao pour créer une nouvelle excursion
            $excursionDao = new ExcursionDao($this->getPdo());
            $nouvelleExcursion = $excursionDao->creer($data);

            // Redirige vers la liste des excursions après création réussie
            if ($nouvelleExcursion) {
                $this->handleVisits($nouvelleExcursion->getId(), $_POST);

                if ($isAjax) {
                    echo json_encode(['success' => true, 'message' => 'Excursion created successfully']);
                    exit;
                }

                //$this->redirect('liste_excursions.php');
            } else {
                if ($isAjax) {
                    echo json_encode(['success' => false, 'message' => 'Error creating excursion']);
                    exit;
                }
                echo "Erreur lors de la création de l'excursion.";
            }
        } else {
            if ($isAjax) {
                echo json_encode(['success' => false, 'message' => 'Form data is missing']);
                exit;
            }
            echo "Erreur : Formulaire vide";
        }
    }

    public function handleVisits(int $excursionId, array $postData): void
{
    // Initialisation des classes DAO
    $composerDao = new ComposerDao($this->getPdo());
    $visiteDao = new VisiteDao($this->getPdo());

    // Boucle pour parcourir les champs qui ont été crées de façon dynamique dans le front
    foreach ($postData as $key => $value) {
        if (strpos($key, 'heure_arrivee_') === 0) {
            // Récuperer l'id de la visite
            $visiteId = str_replace('heure_arrivee_', '', $key);
            $tempsSurPlaceKey = 'temps_sur_place_' . $visiteId;

            // Vérifier que 'temps_sur_place' existe
            if (!isset($postData[$tempsSurPlaceKey])) {
                echo "Erreur: Données manquantes pour la visite ID " . $visiteId . " (temps sur place).";
                continue;
            }

            $heureArr = $value;  // Heure arrivée
            $tempsSurPlace = $postData[$tempsSurPlaceKey];  // Temps passé sur la visite

            // Validation des champs
            if (empty($heureArr) || empty($tempsSurPlace)) {
                echo "Erreur: Données manquantes pour la visite ID " . $visiteId . " (heure d'arrivée ou temps sur place).";
                continue;
            }

            try {
                $dateToday = (new DateTime())->format('Y-m-d');

                // Convertir input strings à des objets DateTime 
                $heureArrObj = new DateTime($dateToday . ' ' . $heureArr);
                $tempsSurPlaceObj = new DateTime($dateToday . ' ' . $tempsSurPlace);

                // Vérifier que la visite existe dans la base de données
                $visite = $visiteDao->findAllAssoc($visiteId);
                if (!$visite) {
                    echo "Erreur : Visite introuvable pour ID " . $visiteId;
                    continue;
                }

                // Création d'une instance de composer
                $composer = new Composer(
                    $heureArrObj,     
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

    // Supprime une excursion en fonction de son ID
    public function supprimer(int $id): void
    {
        $excursionDao = new ExcursionDao($this->getPdo());

        // Si la suppression réussit, redirection vers la liste
        if ($excursionDao->supprimer($id)) {
            $this->redirect('lister_excursions.php');
        } else {
            echo "Erreur lors de la suppression de l'excursion.";
        }
    }

    // Liste toutes les excurion
    
    public function afficher($id): void
    {
        $excursionDao = new Excursion($this->getPdo());
        // On récupère une excursion par son ID
        $carnet = $carnetDao->find($id);

        if ($carnet) {
            // Chargement du template pour afficher un carnet
            $template = $this->getTwig()->load('liste_excursion.html.twig');
            echo $template->render(array(
                'excursion' => $excursion,
            ));
        } else {
            // Si l'excursion n'existe pas, afficher une erreur ou rediriger
            echo "Excursion non trouvé.";
        }
    }
    public function lister()
    {
        $excursionDao = new ExcursionDao($this->getPdo());
        $excursions = $excursionDao->findAll(); // Récupération des données
        
        echo $this->getTwig()->render('liste_excursions.html.twig', [
            'excursions' => $excursions
        ]);
    }
    
    public function afficherExcursions()
{
    $excursionDao = new ExcursionDao($this->getPdo());
    $excursions = $excursionDao->findAll();

    echo $this->getTwig()->render('liste_excursions.html.twig', [
        'excursions' => $excursions
    ]);
}

}