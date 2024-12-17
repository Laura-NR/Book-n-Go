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
                'code_postal' => $_POST['code_postal'],
                'description' => $_POST['description'],
                'titre' => $_POST['titre'],
            ];

            // Utilisation de VisiteDao pour créer une nouvelle Visite
            $visiteDao = new VisiteDao($this->getPdo());
            $nouvelleVisite = $visiteDao->insert($data);
            // Redirige vers la liste des visite après création réussie
            /*$nouvelleVisite*/
            if ($nouvelleVisite) {
                // REDIRECTION A CHANGER POUR LA LISTE DES VISITES
                $this->header('index.php?controleur=visite&methode=lister');
            } else {
                echo "Erreur lors de la création de la visite.";
            }
        } else {
            // Chargement du formulaire de création si aucune soumission n'a eu lieu
            echo $this->getTwig()->render('creation_visite.html.twig');
        }
    }

    public function modifier(): void {
        if (!empty($_POST)) {
            $data = [
                'id' =>$_POST['id'],
                'adresse' => $_POST['adresse'],
                'ville' => $_POST['ville'],
                'code_postal' => $_POST['code_postal'],
                'description' => $_POST['description'],
                'titre' => $_POST['titre'],
            ];

            // Utilisation de VisiteDao pour créer une nouvelle Visite
            $visiteDao = new VisiteDao($this->getPdo());
            $visiteModif = $visiteDao->modify($data);
            // Redirige vers la liste des visite après création réussie
            /*$nouvelleVisite*/
            if ($visiteModif) {
                // REDIRECTION A CHANGER POUR LA LISTE DES VISITES
                $this->redirect('index.php?controleur=visite&methode=lister');
            } else {
                echo "Erreur lors de la création de la visite.";
            }
        } else {
            // Chargement du formulaire de création si aucune soumission n'a eu lieu
            echo $this->getTwig()->render('creation_visite.html.twig');
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

    public function lister(): void{
        $visiteDao = new VisiteDao($this->getPdo());
        $listeVisite = $visiteDao->findAll();

        $template = $this->getTwig()->load("liste_visite.html.twig");

        echo $template->render(array(
            "visite" => $listeVisite,
        ));
    }

    public function listerModif(/*A voir*/int $id_guide): void{

    }
}
?>
