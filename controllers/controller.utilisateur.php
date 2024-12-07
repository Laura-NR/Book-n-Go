<?php
class UtilisateurController extends BaseController {
    private UtilisateurDao $utilisateurDao;

    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
    }

    // Créer un utilisateur (Guide ou Voyageur)
    public function creerUtilisateur(): void {
        if ($_POST) {
            $utilisateur = null;
            
            if (isset($_POST['chemin_certif'])) {
                // Si un certificat est fourni, c'est un Guide
                $utilisateur = new Guide();
                $utilisateur->setCheminCertification($_POST['chemin_certif']);
            } else {
                // Sinon, c'est un Voyageur
                $utilisateur = new Utilisateur();
            }
    
            // Définir les autres informations de l'utilisateur
            $utilisateur->setNom($_POST['nom']);
            $utilisateur->setPrenom($_POST['prenom']);
            $utilisateur->setNumeroTel($_POST['numero_tel']);
            $utilisateur->setMail($_POST['mail']);
            $utilisateur->setMdp($_POST['mdp']);
    
            // Insérer l'utilisateur dans la base de données
            if ($this->utilisateurDao->creer($utilisateur)) {
                echo "Utilisateur créé avec succès.";
            } else {
                echo "Erreur lors de la création de l'utilisateur.";
            }
        }
    }
    
    // Afficher un utilisateur par ID  
    public function afficherUtilisateur(int $id): void {
        $utilisateur = $this->utilisateurDao->find($id);
        if ($utilisateur) {
            echo "Nom: " . $utilisateur->getNom() . "<br>";
            echo "Prénom: " . $utilisateur->getPrenom() . "<br>";
            echo "Téléphone: " . $utilisateur->getNumeroTel() . "<br>";
            echo "Email: " . $utilisateur->getMail() . "<br>";

            // Si c'est un guide, afficher son certificat
            /* if ($utilisateur instanceof Guide) {
                echo $utilisateur->consulterCertificat();
            } */
        } else {
            echo "Utilisateur non trouvé.";
        }
    }

    // Modifier un utilisateur
    public function modifierUtilisateur(int $id): void {
        if ($_POST) {
            $utilisateur = $this->utilisateurDao->find($id);
            if ($utilisateur) {
                $utilisateur->setNom($_POST['nom']);
                $utilisateur->setPrenom($_POST['prenom']);
                $utilisateur->setNumeroTel($_POST['numero_tel']);
                $utilisateur->setMail($_POST['mail']);
                $utilisateur->setMdp($_POST['mdp']);

                if (isset($_POST['chemin_certif'])) {
                    if (!$utilisateur instanceof Guide) {
                        $utilisateur = new Guide();
                    }
                    $utilisateur->setCheminCertification($_POST['chemin_certif']);
                }

                if ($this->utilisateurDao->mettreAJour($utilisateur)) {
                    echo "Utilisateur mis à jour avec succès.";
                } else {
                    echo "Erreur lors de la mise à jour de l'utilisateur.";
                }
            }
        }
    }

    // Supprimer un utilisateur
    public function supprimerUtilisateur(int $id): void {
        if ($this->utilisateurDao->supprimer($id)) {
            echo "Utilisateur supprimé avec succès.";
        } else {
            echo "Erreur lors de la suppression de l'utilisateur.";
        }
    }

    // Lister tous les utilisateurs
    public function listerUtilisateurs(): void {
        $utilisateurs = $this->utilisateurDao->listerTous();
        foreach ($utilisateurs as $utilisateur) {
            echo $utilisateur->getNom() . " " . $utilisateur->getPrenom() . "<br>";
        }
    }
}

?>