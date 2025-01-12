<?php
require_once 'controller.voyageur.php';
require_once 'validation/ajout_guide.php';

/**
 * @file controller.guide.php
 * @class ControllerGuide
 * @brief Classe du contrôleur pour la gestion des guides
 */
class ControllerGuide extends ControllerVoyageur
{

    /**
     * @var Validator
     */
    private Validator $validator; // Instance de la classe Validator
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
        global $reglesValidationInsertionGuide;
        $this->validator = new Validator($reglesValidationInsertionGuide);
    }
    public function call($methode): mixed
    {
        if (method_exists($this, $methode)) {
            // Récupère l'ID si disponible
            if (isset($_GET['id'])) {
                $id = (int) $_GET['id'];
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

    /**
     * Crée un guide avec les données fournies
     * @param array $data Données du formulaire (nom, prenom, numero_tel, mail, mdp et chemin_certif)
     * @return bool True si le guide est créé, sinon false
     */
    public function creerGuide(): bool
    {
        $postData = $this->getPost();
        $fileData = $_FILES;

        if (
            empty($postData) ||
            empty($postData['nom']) ||
            empty($postData['prenom']) ||
            empty($postData['numero_tel']) ||
            empty($postData['mail']) ||
            empty($postData['mdp']) ||
            !isset($fileData['chemin_certif']) ||
            empty($fileData['chemin_certif']['tmp_name'])
        ) {
            echo "Données manquantes pour créer le guide.";
            return false;
        } else {
            $data = array_merge($postData, $fileData);
        }

        if ($this->validator->valider($data)) {
            $nomFichierCertif = basename($data['chemin_certif']['name']);
            try {
                $guide = new Guide();
                $guide->setNom($data['nom']);
                $guide->setPrenom($data['prenom']);
                $guide->setNumeroTel($data['numero_tel']);
                $guide->setMail($data['mail']);
                $guide->setMdp(password_hash($data['mdp'], PASSWORD_DEFAULT));
                $guide->setCheminCertification($nomFichierCertif);  // Utiliser le nom du fichier, pas le chemin complet
                $guide->setDerniereCo(new DateTime());

                // *** Initialisation des nouveaux champs lors de la création ***
                $guide->setTentativesEchouees(0); // Initialiser à 0 lors de la création
                $guide->setStatutCompte('actif'); // Statut actif par défaut
                $guide->setDateDernierEchec(null); // Pas d'échec initial

                $guideDao = new GuideDao($this->getPdo());
                if ($guideDao->creer($guide)) {
                    echo "Guide créé";
                    return true;
                } else {
                    echo "Guide non créé -> erreur liée à la bd";
                    return false;
                }
            } catch (Exception $e) {
                echo "Erreur lors de l'ajout du guide : " . $e->getMessage();
            }
        }

        $donnees = $data;
        $erreurs = $this->validator->getMessagesErreurs();
        $_SESSION['erreurs_inscription'] = $erreurs;
        $_SESSION['donnees_inscription'] = $donnees;

        echo "Données invalides pour créer le guide.";
        return false;
    }



    /**
     * Supprimer un guide de la base de données
     * @param int $id identifiant du guide à supprimer
     * @return void
     */
    public function supprimerGuide(int $id): void
    {
        try {
            $guideDao = new GuideDao($this->getPdo());
            $guide = $guideDao->find($id);
    
            if (!$guide) {
                echo "Erreur : guide non trouvé.";
                return;
            }
    
            // Vérification de la soumission du formulaire de suppression
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'supprimer') {
                if ($guideDao->supprimer($id)) {
                    // Stocke une variable de confirmation dans la session
                    $_SESSION['suppression_reussie'] = true;
                    // Redirige vers la page d'accueil après suppression
                    header("Location: index.php");
                    exit;
                } else {
                    echo "Erreur lors de la suppression du guide.";
                }
            }
    
        } catch (Exception $e) {
            echo "Erreur lors de la suppression : " . $e->getMessage();
        }
    }
    /**
     * @brief Modifier les informations d'un guide existant.
     *
     * Cette méthode modifie les informations d'un guide dans la base de données en fonction de son ID.
     * Si la modification échoue, un message d'erreur est affiché.
     *
     * @param int $id L'ID du guide à modifier.
     *
     * @return void
     */
    /**
     * @brief Modifier les informations d'un guide existant.
     *
     * Cette méthode modifie les informations d'un guide dans la base de données en fonction de son ID.
     * Si la modification échoue, un message d'erreur est affiché.
     *
     * @param int $id L'ID du guide à modifier.
     *
     * @return void
     */
    public function modifierGuide(int $id): void
    {
        try {
            $guideDao = new GuideDao($this->getPdo());
            $guide = $guideDao->find($id);
            //var_dump($guide);

            if (!$guide) {
                echo "Erreur : guide non trouvé.";
                return;
            }

            // Vérification de la soumission du formulaire de modification
            if (isset($_POST['action']) && $_POST['action'] === 'modifier') {
                //var_dump($_POST);
                $postData = $this->getPost();

                if (!empty($postData)) {
                    // Mise à jour des données du guide

                    if (isset($postData['nom'])) $guide->setNom($postData['nom']);
                    if (isset($postData['prenom'])) $guide->setPrenom($postData['prenom']);
                    if (isset($postData['numero_tel'])) $guide->setNumeroTel($postData['numero_tel']);
                    if (isset($postData['mail'])) $guide->setMail($postData['mail']);

                    // *** Modifications pour intégrer les nouveaux champs ***

                    // Mise à jour du nombre de tentatives échouées
                    if (isset($postData['tentatives_echouees'])) {
                        $guide->setTentativesEchouees((int)$postData['tentatives_echouees']);
                    }

                    // Mise à jour du statut du compte
                    if (isset($postData['statut_compte'])) {
                        $guide->setStatutCompte($postData['statut_compte']);
                    }

                    // Mise à jour de la date du dernier échec
                    if (isset($postData['date_dernier_echec'])) {
                        $guide->setDateDernierEchec(new DateTime($postData['date_dernier_echec']));
                    }

                    // Sauvegarde dans la base de données
                    if ($guideDao->maj($guide)) {
                        // Stocke une variable de confirmation dans la session
                        $_SESSION['modification_reussie'] = true;
                        // Redirige vers la page d'affichage normale après la modification
                        header("Location: ?controleur=guide&methode=afficher&id=$id&modification_reussie=true");
                        exit;
                    } else {
                        echo "Erreur lors de la mise à jour du guide.";
                    }
                }
            }
        } catch (Exception $e) {
            echo "Erreur lors de la mise à jour : " . $e->getMessage();
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

    /**
     * Afficher le planning du guide (accessible uniquement au guide connecté)
     */
    public function afficherPlanning(): void
    {
        echo $this->getTwig()->render('planning_guide.html.twig');
    }
}
?>