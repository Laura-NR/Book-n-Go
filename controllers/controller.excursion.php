<?php

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

        array_walk_recursive($visites, function (&$value) {
            if (is_string($value)) {
                $value = utf8_encode($value);   
            }
        });

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
            echo "Erreur : Formulaire vide";
        }
    }

    // Supprime une excursion en fonction de son ID
    public function supprimer(int $id): void
    {
        $excursionDao = new ExcursionDao($this->getPdo());

        // Si la suppression réussit, redirection vers la liste
        if ($excursionDao->supprimer($id)) {
            $this->redirect('ListesExcursions.php');
        } else {
            echo "Erreur lors de la suppression de l'excursion.";
        }
    }
}