<?php
require_once 'controller.voyageur.php';
class ControllerGuide extends ControllerVoyageur
{

    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
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
    // Vérifier si l'utilisateur est un administrateur
    private function isAdmin(): bool
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    // Création d'un guide
    public function creerGuide(): void
    {
        if (!$this->isAdmin()) {
            echo "Accès non autorisé. Vous devez être administrateur pour créer un guide.";
            return;
        }

        $postData = $this->getPost();
        if (
            empty($postData) ||
            !isset($postData['nom'], $postData['prenom'], $postData['numero_tel'], $postData['mail'], $postData['mdp'], $postData['chemin_certif'])
        ) {
            echo "Données manquantes pour créer le guide.";
            return;
        }

        try {
            $guide = new Guide();
            $guide->setNom($postData['nom']);
            $guide->setPrenom($postData['prenom']);
            $guide->setNumeroTel($postData['numero_tel']);
            $guide->setMail($postData['mail']);
            $guide->setMdp($postData['mdp']);
            $guide->setCheminCertification($postData['chemin_certif']);

            $guideDao = new GuideDao($this->getPdo());
            if ($guideDao->creer($guide)) {
                echo "Insertion réalisée avec succès.";
            } else {
                echo "Erreur lors de la création du guide.";
            }
        } catch (Exception $e) {
            echo "Erreur lors de l'ajout du guide : " . $e->getMessage();
        }
    }

    // Modification d'un guide
    public function modifierGuide(int $id): void
    {

        try {
            $guideDao = new GuideDao($this->getPdo());
            $guide = $guideDao->find($id);

            if ($guide && !empty($this->getPost())) {
                $postData = $this->getPost();
                if (isset($postData['nom'])) $guide->setNom($postData['nom']);
                if (isset($postData['prenom'])) $guide->setPrenom($postData['prenom']);
                if (isset($postData['numero_tel'])) $guide->setNumeroTel($postData['numero_tel']);
                if (isset($postData['mail'])) $guide->setMail($postData['mail']);
                if (isset($postData['mdp'])) $guide->setMdp($postData['mdp']);
                if (isset($postData['chemin_certif'])) $guide->setCheminCertification($postData['chemin_certif']);

                if ($guideDao->maj($guide)) {
                    echo "La mise à jour du guide est effectuée.";
                } else {
                    echo "Erreur lors de la mise à jour du guide.";
                }
            } else {
                echo "Erreur : guide non trouvé ou données manquantes.";
            }
        } catch (Exception $e) {
            echo "Erreur lors de la mise à jour : " . $e->getMessage();
        }
    }

    // Suppression d'un guide
    public function supprimerGuide(int $id=1): void
    {
        try {
            $guideDao = new GuideDao($this->getPdo());
            if ($guideDao->supprimer($id)) {
                echo "Guide supprimé avec succès.";
                $this->redirect('index.php');
            } else {
                echo "Erreur lors de la suppression du guide ou guide non trouvé.";
            }
        } catch (Exception $e) {
            echo "Erreur lors de la suppression : " . $e->getMessage();
        }
    }

    // Lister tous les guides (accessible par tous les utilisateurs)
    public function lister(): void
    {
        try {
            $guideDao = new GuideDao($this->getPdo());
            $guides = $guideDao->findAll();

            $template = $this->getTwig()->load('guide.html.twig');
            echo $template->render([
                'guides' => $guides,
                'menu' => "guide"
            ]);
        } catch (Exception $e) {
            echo "Erreur lors de la récupération des guides : " . $e->getMessage();
        }
    }

    // Afficher les détails d'un guide spécifique (accessible par tous les utilisateurs)
    public function afficher(int $id = 1): void
    {
        try {
            $guideDao = new GuideDao($this->getPdo());
            $guide = $guideDao->findAssoc($id);

            if (!$guide) {
                echo "Guide avec id $id pas trouvé.";
                return;
            }

            echo $this->getTwig()->render('pageInformationsGuide.html.twig', [
                'guide' => $guide,
                'menu' => "guide_detail"
            ]);
        } catch (Exception $e) {
            echo "Erreur lors de l'affichage du guide : " . $e->getMessage();
        }
    }

    // Voir le certificat du guide (accessible uniquement aux administrateurs)
    public function voirCertification(int $id): void
    {
        if (!$this->isAdmin()) {
            echo "Accès non autorisé. Vous devez être administrateur pour voir le certificat.";
            return;
        }

        try {
            $guideDao = new GuideDao($this->getPdo());
            $guide = $guideDao->find($id);

            if ($guide) {
                $cheminCertificat = $guideDao->getCheminCertificatParId($id);
                if ($cheminCertificat && file_exists($cheminCertificat)) {
                    header('Content-Type: bookngo/pdf');
                    header('Content-Disposition: attachment; filename="' . basename($cheminCertificat) . '"');
                    readfile($cheminCertificat);
                    exit;
                } else {
                    echo "Le certificat pour ce guide est introuvable.";
                }
            } else {
                echo "Guide non trouvé.";
            }
        } catch (Exception $e) {
            echo "Erreur lors de la consultation du certificat : " . $e->getMessage();
        }
    }
}
