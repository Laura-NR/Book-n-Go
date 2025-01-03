<?php
require_once 'controller.class.php';
require_once 'validation/ajout_voyageur.php';

class ControllerVoyageur extends BaseController {

    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
        global $reglesValidationInscriptionVoyageur;
        $this->validator = new Validator($reglesValidationInscriptionVoyageur);
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
    
    // Création d'un voyageur
    public function creerVoyageur(): bool {
        $postData = $this->getPost();
        // Vérification des données nécessaires
        if (empty($postData) ||
            !isset($postData['nom'],
                $postData['prenom'],
                $postData['numero_tel'],
                $postData['mail'],
                $postData['mdp'])) {
            // echo "Données manquantes pour créer le voyageur.";
            return false; // Retourner immédiatement si les données sont manquantes
        }
        if ($this->validator->valider($postData)) {
            try {
                // Création du voyageur
                $voyageur = new Voyageur();
                $voyageur->setNom($postData['nom']);
                $voyageur->setPrenom($postData['prenom']);
                $voyageur->setNumeroTel($postData['numero_tel']);
                $voyageur->setMail($postData['mail']);
                $voyageur->setMdp(password_hash($postData['mdp'], PASSWORD_DEFAULT));
                //var_dump($voyageur);
                $voyageur->setDerniereCo(new DateTime());
                // Utilisation de VoyageurDao pour insérer le voyageur
                $voyageurDao = new VoyageurDao($this->getPdo());
                if ($voyageurDao->creer($voyageur)) {
                    echo "Voyageur créé";
                    return true;
                } else {
                    echo "Voyageur non créé -> erreur liée à la bd";
                    return false;
                }
            } catch (Exception $e) {
                echo "Erreur lors de l'ajout du voyageur : " . $e->getMessage();
            }
        }
        $donnees = $postData;
        $erreurs = $this->validator->getMessagesErreurs();
        //var_dump($erreurs);
        $_SESSION['erreurs_inscription'] = $erreurs;
        //var_dump($_SESSION['erreurs_commentaire']);
        $_SESSION['donnees_inscription'] = $donnees;

        //var_dump($this->validator->valider($data));
        echo "Données invalides pour créer le Voyageur.";
        return false;
    }

    // Modification d'un voyageur
    public function supprimerVoyageur(int $id): void
    {
        try {
            $voyageurDao = new VoyageurDao($this->getPdo());
            $voyageur = $voyageurDao->find($id);

            if (!$voyageur) {
                echo "Erreur : voyageur non trouvé.";
                return;
            }

            // Vérification de la soumission du formulaire de suppression
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'supprimer') {
                if ($voyageurDao->supprimer($id)) {
                    // Stocke une variable de confirmation dans la session
                    $_SESSION['suppression_reussie'] = true;
                    // Redirige vers la page d'accueil après suppression
                    header("Location: index.php");
                    exit;
                } else {
                    echo "Erreur lors de la suppression du voyageur.";
                }
            }

        } catch (Exception $e) {
            echo "Erreur lors de la suppression : " . $e->getMessage();
        }
    }

    public function modifierVoyageur(int $id): void
    {
        try {
            $voyageurDao = new VoyageurDao($this->getPdo());
            $voyageur = $voyageurDao->find($id);
            //var_dump($guide);

            if (!$voyageur) {
                echo "Erreur : voyageur non trouvé.";
                return;
            }

            // Vérification de la soumission du formulaire de modification
            if (/*isset($_POST['nom']) &&*/ isset($_POST['action']) && $_POST['action'] === 'modifier') {
                //var_dump($_POST);
                $postData = $this->getPost();





                if (!empty($postData)) {
                    // Mise à jour des données du voyageur

                    if (isset($postData['nom'])) $voyageur->setNom($postData['nom']);
                    if (isset($postData['prenom'])) $voyageur->setPrenom($postData['prenom']);
                    if (isset($postData['numero_tel'])) $voyageur->setNumeroTel($postData['numero_tel']);
                    if (isset($postData['mail'])) $voyageur->setMail($postData['mail']);
                    //var_dump($voyageur);

                    // Sauvegarde dans la base de données
                    if ($voyageurDao->mettreAJour($voyageur)) {
                        // Stocke une variable de confirmation dans la session
                        $_SESSION['modification_reussie'] = true;
                        // Redirige vers la page d'affichage normale après la modification
                        header("Location: ?controleur=voyageur&methode=afficher&id=$id&modification_reussie=true");
                        exit;
                    } else {
                        echo "Erreur lors de la mise à jour du voyageur.";
                    }
                }
            }

        } catch (Exception $e) {
            echo "Erreur lors de la mise à jour : " . $e->getMessage();
        }
    }
    // Lister tous les voyageurs

    public function lister(): void {
    try {
        // Utilisation de la méthode listerTousVoyageurs pour récupérer tous les voyageurs
        $voyageurDao = new VoyageurDao($this->getPdo());
        // $voyageurs = $voyageurDao->listerTousVoyageurs(); // Récupère tous les voyageurs via la méthode listerTousVoyageurs
        // $voyageurs = $voyageurDao->listerTousVoyageurs(); // Récupère tous les voyageurs via la méthode listerTousVoyageurs

        // Chargement du template pour lister les voyageurs
        $template = $this->getTwig()->load('voyageurList.twig');

        // Affichage du template avec les données des voyageurs
        echo $template->render([
            // 'voyageurs' => $voyageurs,
            // 'voyageurs' => $voyageurs,
            'menu' => "voyageur"
        ]);
    } catch (Exception $e) {
        echo "Erreur lors de la récupération des voyageurs : " . $e->getMessage();
    }
}


    // Afficher les détails d'un voyageur spécifique
    public function afficher(int $id): void
    {
        try {
            $voyageurDao = new VoyageurDao($this->getPdo());
            $voyageur = $voyageurDao->findAssoc($id);

            if (!$voyageur) {
                echo "voyageur avec id $id pas trouvé.";
                return;
            }

            $editMode = isset($_GET['editMode']) && $_GET['editMode'] === 'true';

            echo $this->getTwig()->render('pageInformationsVoyageur.html.twig', [
                'voyageur' => $voyageur,
                'menu' => "voyageur_detail",
                'editMode' => $editMode, // Mode d'édition
            ]);
        } catch (Exception $e) {
            echo "Erreur lors de l'affichage du voyageur : " . $e->getMessage();
        }
    }
}
?>
