<?php

class ControllerVisite extends BaseController {
    public function __construct(\Twig\Loader\FilesystemLoader $loader, \Twig\Environment $twig) {
        parent::__construct($loader, $twig);
    }

    // Affiche le formulaire de création de visite et enregistre les données si envoyées
    public function creer(): void {
        // Vérifie si le formulaire a été soumis
        if (!empty($this->getPost())) {
            $data = [
                'capacite' => $this->getPost()['capacite'] ?? '',
                'nom' => $this->getPost()['nom'] ?? '',
                'chemin_image' => $this->getPost()['chemin_image'] ?? '',
                'date_visite' => $this->getPost()['date_visite'] ?? '',
                'description' => $this->getPost()['description'] ?? '',
                'public' => $this->getPost()['public'] ?? 1, // 1 pour public, 0 pour privé
                'id_guide' => $this->getPost()['id_guide'] ?? ''
            ];

            // Utilisation de VisiteDao pour créer une nouvelle visite
            $visiteDao = new VisiteDao($this->getPdo());
            $nouvelleVisite = $visiteDao->creer($data);

            // Redirige vers la liste des visites après création réussie
            if ($nouvelleVisite) {
                $this->redirect('ListesVisites.php');
            } else {
                echo "Erreur lors de la création de la visite.";
            }
        } else {
            // Chargement du formulaire de création si aucune soumission n'a eu lieu
            echo $this->getTwig()->render('creerVisite.twig');
        }
    }

    // Supprime une visite en fonction de son ID
    public function supprimer(int $id): void {
        $visiteDao = new VisiteDao($this->getPdo());

        // Si la suppression réussit, redirection vers la liste
        if ($visiteDao->supprimer($id)) {
            $this->redirect('ListesVisites.php');
        } else {
            echo "Erreur lors de la suppression de la visite.";
        }
    }
}
?>
