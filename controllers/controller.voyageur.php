<?php

class ControllerVoyageur extends BaseController {
    public function __construct(\Twig\Environment $twig) {
        parent::__construct($twig);
    }

    // Création d'un voyageur
    public function creerVoyageur(): void {
        if (!empty($this->getPost())) {
            $voyageur = new Voyageur();
            $voyageur->setNom($this->getPost()['nom'] ?? '')
                     ->setPrenom($this->getPost()['prenom'] ?? '')
                     ->setNumeroTel($this->getPost()['numero_tel'] ?? '')
                     ->setMail($this->getPost()['mail'] ?? '')
                     ->setMdp(password_hash($this->getPost()['mdp'] ?? '', PASSWORD_BCRYPT));
            
            $voyageurDao = new VoyageurDao($this->getPdo());
        //     if ($voyageurDao->creer($voyageur)) {
        //         $this->redirect('listeVoyageurs.php');
        //     } else {
        //         echo "Erreur lors de la création du voyageur.";
        //     }
        // } else {
        //     echo "Données manquantes pour créer le voyageur.";
        }
    }

    // Modification d'un voyageur
    public function modifierVoyageur(int $id): void {
        $voyageurDao = new VoyageurDao($this->getPdo());
        $voyageur = $voyageurDao->find($id);

        if ($voyageur && !empty($this->getPost())) {
            $voyageur->setNom($this->getPost()['nom'] ?? $voyageur->getNom())
                     ->setPrenom($this->getPost()['prenom'] ?? $voyageur->getPrenom())
                     ->setNumeroTel($this->getPost()['numero_tel'] ?? $voyageur->getNumeroTel())
                     ->setMail($this->getPost()['mail'] ?? $voyageur->getMail())
                     ->setMdp(password_hash($this->getPost()['mdp'] ?? $voyageur->getMdp(), PASSWORD_BCRYPT));

        //     if ($voyageurDao->maj($voyageur)) {
        //         $this->redirect('listeVoyageurs.php');
        //     } else {
        //         echo "Erreur lors de la mise à jour du voyageur.";
        //     }
        // } else {
        //     echo "Erreur : voyageur non trouvé ou données manquantes.";
        }
    }

    // Suppression d'un voyageur
    public function supprimerVoyageur(int $id): void {
        $voyageurDao = new VoyageurDao($this->getPdo());
    //     if ($voyageurDao->supprimer($id)) {
    //         $this->redirect('listeVoyageurs.php');
    //     } else {
    //         echo "Erreur lors de la suppression du voyageur.";
    //     }
    }
}

?>
