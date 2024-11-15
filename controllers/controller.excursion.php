<?php

class ControllerExcursion extends BaseController {
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
            ];

            $id_guide = $_SESSION['id_guide'] ?? null;
            if ($id_guide) {
                $data['id_guide'] = $id_guide;
            } else {
                echo "Erreur : L'identifiant du guide est manquant.";
                return;
            }

            // Utilisation de ExcursionDao pour créer une nouvelle excursion
            $excursionDao = new ExcursionDao($this->getPdo());
            $nouvelleExcursion = $excursionDao->creer($data);

            // Redirige vers la liste des excursions après création réussie
            if ($nouvelleExcursion) {
                $visites = $this->getPost()['visites'] ?? [];
                $composerDAO = new ComposerDao($this->getPdo());

                foreach ($visites as $visiteId) {
                    $composer = new Composer(
                        new DateTime($this->getPost()['heure_arrivee'][$visiteId] ?? null),
                        new DateTime($this->getPost()['temps_sur_place'][$visiteId] ?? null),
                        $nouvelleExcursion,
                        (new VisiteDao($this->getPdo()))->find($visiteId)
                    );

                    if ($composerDAO->creer($composer)) {
                        echo "Erreur : Impossible de créer le composer.";
                    }
                }

                $this->redirect('liste_excursions.php');
            } else {
                echo "Erreur lors de la création de l'excursion.";
            }
        } else {
            //Récuperation des toutes les visites pour les afficher dans le menu déroulant
            $visiteDao = new VisiteDao($this->getPdo());
            $visites = $visiteDao->findAllAssoc();
            var_dump($visites);

            // Chargement du formulaire de création 
            echo $this->getTwig()->render('creation_excursion.html.twig', [
                'visites' => $visites
            ]);
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
