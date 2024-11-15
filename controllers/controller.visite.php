<?php

class ControllerVisite extends BaseController {
    public function __construct(\Twig\Loader\FilesystemLoader $loader, \Twig\Environment $twig) {
        parent::__construct($loader, $twig);
    }

    // Affiche le formulaire de création d'excursion et enregistre les données si envoyées
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

            // Utilisation de ExcursionDao pour créer une nouvelle excursion
            $excursionDao = new ExcursionDao($this->getPdo());
            $nouvelleExcursion = $excursionDao->creer($data);

            // Redirige vers la liste des excursions après création réussie
            if ($nouvelleExcursion) {
                $this->redirect('ListesExcursions.php');
            } else {
                echo "Erreur lors de la création de l'excursion.";
            }
        } else {
            // Chargement du formulaire de création si aucune soumission n'a eu lieu
            echo $this->getTwig()->render('creation_excursion.html.twig');
        }
    }

    // Supprime une excursion en fonction de son ID
    public function supprimer(int $id): void {
        $excursionDao = new ExcursionDao($this->getPdo());

        // Si la suppression réussit, redirection vers la liste
        if ($excursionDao->supprimer($id)) {
            $this->redirect('ListesExcursions.php');
        } else {
            echo "Erreur lors de la suppression de l'excursion.";
        }
    }
}
?>
