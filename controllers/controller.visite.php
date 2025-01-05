<?php
require_once 'controller.class.php';
require_once 'validation/ajout_visite.php';

class ControllerVisite extends BaseController
{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
        global $reglesValidationInsertionVisite;
        $this->validator = new Validator($reglesValidationInsertionVisite);
    }

    // Affiche le formulaire de création d'visite et enregistre les données si envoyées
    public function creer(): void
    {
        // Vérifie si le formulaire a été soumis
        if (
            empty($_POST) ||
            empty($_POST['adresse']) ||
            empty($_POST['ville']) ||
            empty($_POST['codePostal']) ||
            empty($_POST['titre'])
        ) {
            echo "Données manquantes pour créer la visite.";
        }

        $data = [
            'adresse' => $_POST['adresse'],
            'ville' => $_POST['ville'],
            'codePostal' => $_POST['codePostal'],
            'description' => $_POST['description'],
            'titre' => $_POST['titre'],
            'idGuide' => $_SESSION['user_id']
        ];

        if ($this->validator->valider($data)) {
            try {
                // Utilisation de VisiteDao pour créer une nouvelle Visite
                $visiteDao = new VisiteDao($this->getPdo());
                $nouvelleVisite = $visiteDao->insert($data);
                // Redirige vers la liste des visite après création réussie
                /*$nouvelleVisite*/
                if ($nouvelleVisite && $_GET['isExcursion'] === '1') {
                    $this->redirect('excursion', 'afficherCreer');
                } elseif ($nouvelleVisite && $_GET['isExcursion'] === '0') {
                    $this->redirect('visite', 'lister');
                } else {
                    echo "Erreur lors de la création de la visite.";
                }
            } catch (Exception $e) {
                echo "Erreur lors de l'ajout de la visite : " . $e->getMessage();
            }
        }

        $erreurs = $this->validator->getMessagesErreurs();
        if (!empty($erreurs)) {
            echo $this->getTwig()->render('formulaire_visite.html.twig', ['isEdit' => false, 'visite' => null, 'isExcursion' => $_GET["isExcursion"], 'erreurs' => $erreurs]);
        }
    }

    public function redirectCreer(): void
    {
        echo $this->getTwig()->render('formulaire_visite.html.twig', ['isEdit' => false, 'visite' => null, 'isExcursion' => $_GET["isExcursion"]]);
    }

    public function redirectModifier(): void
    {
        $id = $_POST['visite_id'];
        $visiteDao = new VisiteDao($this->getPdo());
        $visite = $visiteDao->find($id);

        if ($visite) {
            echo $this->getTwig()->render('formulaire_visite.html.twig', ['isEdit' => true, 'visite' => $visite]);
        } else {
            echo "Visite non trouvée.";
        }
    }

    public function modifier(): void
    {

        $data = [
            'id' => $_POST['id'],
            'adresse' => $_POST['adresse'],
            'ville' => $_POST['ville'],
            'codePostal' => $_POST['codePostal'],
            'description' => $_POST['description'],
            'titre' => $_POST['titre'],
        ];
        

        if ($this->validator->valider($data)) {
            try {
                // Utilisation de VisiteDao pour créer une nouvelle Visite
                $visiteDao = new VisiteDao($this->getPdo());
                $visiteModif = $visiteDao->modify($data);
                // Redirige vers la liste des visite après création réussie
                if ($visiteModif) {
                    $this->redirect('visite', 'lister');
                } else {
                    echo "Erreur lors de la création de la visite.";
                }
            } catch (Exception $e) {
                echo "Erreur lors de l'ajout de la visite : " . $e->getMessage();
            }
        }

        $erreurs = $this->validator->getMessagesErreurs();
        if (!empty($erreurs)) {
            echo $this->getTwig()->render('formulaire_visite.html.twig', ['isEdit' => true, 'visite' => $data, 'erreurs' => $erreurs]);
        }
    }


    // Supprime une visite en fonction de son ID
    // public function supprimer(int $id): void {
    //     $visiteDao = new VisiteDao($this->getPdo());

    //     // Si la suppression réussit, redirection vers la liste
    //     if ($visiteDao->supprimer($id)) {
    //         $this->redirect('listes_visites.php');
    //     } else {
    //         echo "Erreur lors de la suppression de la visite.";
    //     }
    // }

    public function lister(): void
    {
        $checkbox = isset($_POST['checkbox']);
        $visiteDao = new VisiteDao($this->getPdo());
        if (!$checkbox)
            $listeVisite = $visiteDao->findAll();
        else {
            $id_guide = $_SESSION['user_id'];
            $listeVisite = $visiteDao->findByGuide($id_guide);
        }
        $template = $this->getTwig()->load("liste_visite.html.twig");

        echo $template->render(array(
            'etatCheck' => $checkbox,
            "visites" => $listeVisite,
        ));
    }
}
