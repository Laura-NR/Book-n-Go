<?php

class ControllerVoyageur extends BaseController {

    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
    }

    public function call($methode): mixed
    {
        if (method_exists($this, $methode)) {
            // Récupère l'ID si disponible
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                return $this->$methode($id); // Appelle la méthode avec l'ID si disponible
            } else {
                return $this->$methode(); // Appelle la méthode sans ID
            }
        } else {
            throw new Exception("Méthode $methode non trouvée dans le contrôleur.");
        }
    }

    // Création d'un voyageur
    public function creerVoyageur(): void {
        // Vérification des données nécessaires
        if (empty($this->getPost()) || 
            !isset($this->getPost()['nom'], $this->getPost()['prenom'], $this->getPost()['numero_tel'], $this->getPost()['mail'], $this->getPost()['mdp'])) {
            echo "Données manquantes pour créer le voyageur.";
            return; // Retourner immédiatement si les données sont manquantes
        }

        try {
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
        } catch (Exception $e) {
            echo "Erreur lors de l'ajout du voyageur : " . $e->getMessage();
        }
    }

    // Modification d'un voyageur
    public function modifierVoyageur(int $id): void {
        try {
            $voyageurDao = new VoyageurDao($this->getPdo());
            $voyageur = $voyageurDao->find($id);

            if ($voyageur && !empty($this->getPost())) {
                if (isset($this->getPost()['nom'])) $voyageur->setNom($this->getPost()['nom']);
                if (isset($this->getPost()['prenom'])) $voyageur->setPrenom($this->getPost()['prenom']);
                if (isset($this->getPost()['numero_tel'])) $voyageur->setNumeroTel($this->getPost()['numero_tel']);
                if (isset($this->getPost()['mail'])) $voyageur->setMail($this->getPost()['mail']);
                if (isset($this->getPost()['mdp'])) $voyageur->setMdp($this->getPost()['mdp']);

                if ($voyageurDao->mettreAJour($voyageur)) {
                    echo "La mise à jour du voyageur est effectuée.";
                } else {
                    echo "Erreur lors de la mise à jour du voyageur.";
                }
            } else {
                echo "Erreur : voyageur non trouvé ou données manquantes.";
            }
        } catch (Exception $e) {
            echo "Erreur lors de la mise à jour : " . $e->getMessage();
        }
    }

    // Suppression d'un voyageur
    public function supprimerVoyageur(int $id): void {
        try {
            $voyageurDao = new VoyageurDao($this->getPdo());
            if ($voyageurDao->supprimer($id)) {
                echo "Voyageur supprimé avec succès.";
            } else {
                echo "Erreur lors de la suppression du voyageur ou voyageur non trouvé.";
            }
        } catch (Exception $e) {
            echo "Erreur lors de la suppression : " . $e->getMessage();
        }
    }

    // Lister tous les voyageurs

    public function lister(): void {
    try {
        // Utilisation de la méthode listerTousVoyageurs pour récupérer tous les voyageurs
        $voyageurDao = new VoyageurDao($this->getPdo());
        $voyageurs = $voyageurDao->listerTousVoyageurs(); // Récupère tous les voyageurs via la méthode listerTousVoyageurs

        // Chargement du template pour lister les voyageurs
        $template = $this->getTwig()->load('voyageurList.twig');

        // Affichage du template avec les données des voyageurs
        echo $template->render([
            'voyageurs' => $voyageurs, 
            'menu' => "voyageur"
        ]);
    } catch (Exception $e) {
        echo "Erreur lors de la récupération des voyageurs : " . $e->getMessage();
    }
}


    // Afficher les détails d'un voyageur spécifique
    public function afficher(int $id): void {
        try {
            $voyageurDao = new VoyageurDao($this->getPdo());
            $voyageur = $voyageurDao->find($id);

            // Chargement du template pour afficher les détails du voyageur
            $template = $this->getTwig()->load('pageInformationsVoyageur.html.twig');

            // Affichage du template avec les données du voyageur
            echo $template->render([
                'voyageur' => $voyageur,
                'menu' => "voyageur_detail"
            ]);
        } catch (Exception $e) {
            echo "Erreur lors de l'affichage du voyageur : " . $e->getMessage();
        }
    }
}
?>
