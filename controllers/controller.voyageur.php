<?php

class ControllerVoyageur extends BaseController {
    public function __construct(\Twig\Loader\FilesystemLoader $loader, \Twig\Environment $twig) {
        parent::__construct($loader, $twig);
    }


    // Création d'un voyageur
    public function creerVoyageur(): void {
        // Vérification des données nécessaires
        if (empty($this->getPost()) || 
            !isset($this->getPost()['nom'], $this->getPost()['prenom'], $this->getPost()['numero_tel'], $this->getPost()['mail'], $this->getPost()['mdp'])) {
            echo "Données manquantes pour créer le voyageur.";
            return; // Retourner immédiatement si les données sont manquantes
        }
    
        // Création du voyageur
        $voyageur = new Voyageur();
        $voyageur->setNom($this->getPost()['nom']);
        $voyageur->setPrenom($this->getPost()['prenom']);
        $voyageur->setNumeroTel($this->getPost()['numero_tel']);
        $voyageur->setMail($this->getPost()['mail']);
        $voyageur->setMdp($this->getPost()['mdp']);
        
        // Utilisation de VoyageurDao pour insérer le voyageur
        $voyageurDao = new VoyageurDao($this->getPdo());
        if ($voyageurDao->creer($voyageur)) {
            echo "Insertion réalisée avec succès.";
        } else {
            echo "Erreur lors de la création du voyageur.";
        }
    }
    
    
    // Modification d'un voyageur
    public function modifierVoyageur(int $id): void {
        $voyageurDao = new VoyageurDao($this->getPdo());
        $voyageur = $voyageurDao->find($id);
    
        if ($voyageur && !empty($this->getPost())) {
            if (isset($this->getPost()['nom'])) $voyageur->setNom($this->getPost()['nom']);
            if (isset($this->getPost()['prenom'])) $voyageur->setPrenom($this->getPost()['prenom']);
            if (isset($this->getPost()['numero_tel'])) $voyageur->setNumeroTel($this->getPost()['numero_tel']);
            if (isset($this->getPost()['mail'])) $voyageur->setMail($this->getPost()['mail']);
            if (isset($this->getPost()['mdp'])) $voyageur->setMdp($this->getPost()['mdp']);
    
            if ($voyageurDao->maj($voyageur)) {
                echo "La mise à jour du voyageur est effectuée.";
            } else {
                echo "Erreur lors de la mise à jour du voyageur.";
            }
        } else {
            echo "Erreur : voyageur non trouvé ou données manquantes.";
        }
    }
    
    // Suppression d'un voyageur
    public function supprimerVoyageur(int $id): void {
        $voyageurDao = new VoyageurDao($this->getPdo());
        if ($voyageurDao->supprimer($id)) {
            echo "Voyageur supprimé avec succès.";
        } else {
            echo "Erreur lors de la suppression du voyageur ou voyageur non trouvé.";
        }
    }
    
}

?>
