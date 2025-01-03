<?php
require_once 'controller.class.php';
class ControllerVisite extends BaseController {
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
    }

    // Affiche le formulaire de création d'visite et enregistre les données si envoyées
    public function creer(): void {
        // Vérifie si le formulaire a été soumis
        
        if (!empty($_POST)) {
            $data = [
                'adresse' => $_POST['adresse'],
                'ville' => $_POST['ville'],
                'codePostal' => $_POST['codePostal'],
                'description' => $_POST['description'],
                'titre' => $_POST['titre'],
                'idGuide' => 10, /*$_SESSION['id'],*/
            ];

            // Utilisation de VisiteDao pour créer une nouvelle Visite
            $visiteDao = new VisiteDao($this->getPdo());
            $nouvelleVisite = $visiteDao->insert($data);
            // Redirige vers la liste des visite après création réussie
            /*$nouvelleVisite*/
            if ($nouvelleVisite /*&& parametre excursion = TRUE*/) {
                $this->redirect('visite','lister');
            }
            //elseif ($nouvelleVisite /*&&parametre excursion = FALSE*/){
            //    $this->redirect('visite','lister');
            //}
            else {
                echo "Erreur lors de la création de la visite.";
            }
        } else {
            // Chargement du formulaire de création si aucune soumission n'a eu lieu
            echo $this->getTwig()->render('creation_visite.html.twig', ['isEdit' => false, 'visite' => null]);
        }
    }

    public function redirectCreer(): void {
            echo $this->getTwig()->render('creation_visite.html.twig', ['isEdit' => false, 'visite' => null]);
    }

    public function redirectModifier(): void {
        $id = $_POST['id'];
        $visiteDao = new VisiteDao($this->getPdo());
        $visite = $visiteDao->find($id);

        if ($visite) {
            echo $this->getTwig()->render('creation_visite.html.twig', ['isEdit' => true, 'visite' => $visite]);
        } else {
            echo "Visite non trouvée.";
        }
    }

    public function modifier(): void {
        if (!empty($_POST)) {
            $data = [
                'id' =>$_POST['id'],
                'adresse' => $_POST['adresse'],
                'ville' => $_POST['ville'],
                'codePostal' => $_POST['codePostal'],
                'description' => $_POST['description'],
                'titre' => $_POST['titre'],
            ];

            // Utilisation de VisiteDao pour créer une nouvelle Visite
            $visiteDao = new VisiteDao($this->getPdo());
            $visiteModif = $visiteDao->modify($data);
            // Redirige vers la liste des visite après création réussie
            /*$nouvelleVisite*/
            if ($visiteModif) {
                $this->redirect('visite','lister');
            } else {
                echo "Erreur lors de la création de la visite.";
            }
        } else {
            // Chargement du formulaire de création si aucune soumission n'a eu lieu
            echo $this->getTwig()->render('creation_visite.html.twig', ['isEdit' => true, 'visite' => $visite]);
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
            $id_guide = 10 /*$_SESSION['id'],*/;
            $listeVisite = $visiteDao->findByGuide($id_guide);
        }
        $template = $this->getTwig()->load("liste_visite.html.twig");

        echo $template->render(array(
            'etatCheck' => $checkbox,
            "visites" => $listeVisite,
        ));
    }
}
?>
