<?php

class ControllerVisite extends BaseController {
    public function __construct(\Twig\Environment $twig) {
        parent::__construct($twig);
    }

    // Affiche le formulaire de création de visite
    public function creer(): void {
        $data = [
            'capacite' => $this->getPost()['capacite'],
            'nom' => $this->getPost()['nom'],
            'chemin_image' => $this->getPost()['chemin_image'],
            'date_visite' => $this->getPost()['date_visite'],
            'description' => $this->getPost()['description'],
            'public' => $this->getPost()['public'],
            'id_guide' => $this->getPost()['id_guide']
        ];
    
        $visiteDao = new VisiteDao($this->getPdo());
        $nouvelleVisite = $visiteDao->create($data);
    
        if ($nouvelleVisite) {
            $this->redirect('ListesVisites.php');
        } else {
            echo "Erreur lors de la création de la visite.";
        }
    }
    

    // Enregistre une nouvelle visite dans la base de données
    public function enregistrer(): void {
        // Vérifie si les données POST sont bien présentes
        if (!empty($this->getPost())) {
            $visiteDao = new VisiteDao($this->getPdo());

            // Récupère les données du formulaire
            $nom = $this->getPost()['nom'] ?? '';
            $description = $this->getPost()['description'] ?? '';
            $dateVisite = $this->getPost()['date_visite'] ?? '';
            $idGuide = $this->getPost()['id_guide'] ?? '';

            // Crée la visite
            $visiteDao->creerVisite($nom, $description, $dateVisite, $idGuide);

            // Redirige vers la liste des visites après la création
            $this->redirect('listeVisites.php');
        } else {
            echo "Erreur : données de visite manquantes.";
        }
    }

}
?>
