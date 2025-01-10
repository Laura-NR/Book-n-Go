<?php
require_once 'controller.class.php';
require_once 'validation/ajout_visite.php';

/**
 * @class ControllerVisite
 * @brief Classe du contrôleur pour la gestion des visites
 */
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


    /**
     * @brief Redirige vers le formulaire de création d'une visite.
     *
     * Cette méthode affiche le formulaire de création d'une visite en utilisant le template
     * `formulaire_visite.html.twig`. Elle indique que le formulaire est en mode création
     * et non en mode édition, et détermine si la visite fait partie d'une excursion en fonction
     * du paramètre `isExcursion` passé dans l'URL.
     *
     * @return void
     */
    public function redirectCreer(): void
    {
        echo $this->getTwig()->render('formulaire_visite.html.twig', ['isEdit' => false, 'visite' => null, 'isExcursion' => $_GET["isExcursion"]]);
    }

    /**
     * @brief Redirige vers le formulaire de modification d'une visite.
     *
     * Cette méthode affiche le formulaire de modification d'une visite en utilisant le template
     * `formulaire_visite.html.twig`. Elle indique que le formulaire est en mode édition
     * et non en mode création, et cherche la visite correspondant au paramètre `visite_id`
     * passé dans le formulaire. Si la visite existe, elle est transmise au template.
     * Sinon, un message d'erreur est affiché.
     *
     * @return void
     */
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

    /**
     * @brief Modifie une visite.
     *
     * Cette méthode modifie une visite en validant les données du formulaire
     * et en appelant la méthode `modify` de VisiteDao.
     * Si la validation est réussie et que la modification est effectuée,
     * elle redirige vers la liste des visites.
     * Sinon, elle affiche le formulaire de modification avec les erreurs.
     *
     * @return void
     */
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

    /**
     * @brief Affiche la liste des visites.
     *
     * Si le champ checkbox est décochée, cette méthode affiche les visites associées au guide
     * connecté. Sinon, elle affiche toutes les visites enregistrées.
     *
     * @return void
     */
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
