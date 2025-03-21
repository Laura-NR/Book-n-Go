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
                if (isset($data['chemin_certif']['name']) && $data['chemin_certif']['error'] === UPLOAD_ERR_OK) {
                    $nomImage = $data["mail"] . '_' . 'certification' . '.pdf';
                    $cheminCertif = 'certifications/' . $nomImage;
                }
                $guide = new Guide();
                $guide->setNom($data['nom']);
                $guide->setPrenom($data['prenom']);
                $guide->setNumeroTel($data['numero_tel']);
                $guide->setMail($data['mail']);
                $guide->setMdp(password_hash($data['mdp'], PASSWORD_DEFAULT));
                $guide->setCheminCertification($nomImage);  // Utiliser le nom du fichier, pas le chemin complet
                $guide->setDerniereCo(new DateTime());

                // *** Initialisation des nouveaux champs lors de la création ***
                $guide->setTentativesEchouees(0); // Initialiser à 0 lors de la création
                $guide->setStatutCompte('actif'); // Statut actif par défaut
                $guide->setDateDernierEchec(null); // Pas d'échec initial

                $guideDao = new GuideDao($this->getPdo());
                if ($guideDao->creer($guide)) {
                    echo "Guide créé";
                    // Gestion de l'upload d'image
                    move_uploaded_file($data['chemin_certif']['tmp_name'], $cheminCertif);
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
                    //deconnecte le compte
                    session_destroy();
                    //Redirige vers la page d'accueil après suppression
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

            if (!$guide) {
                echo "Erreur : guide non trouvé.";
                return;
            }

            // Vérification de la soumission du formulaire de modification
            if (isset($_POST['action']) && $_POST['action'] === 'modifier') {
                $postData = $_POST; // Utiliser $_POST directement

                if (!empty($postData)) {
                    // Mise à jour des données du guide
                    if (isset($postData['nom'])) $guide->setNom($postData['nom']);
                    if (isset($postData['prenom'])) $guide->setPrenom($postData['prenom']);
                    if (isset($postData['numero_tel'])) $guide->setNumeroTel($postData['numero_tel']);
                    if (isset($postData['mail'])) $guide->setMail($postData['mail']);

                    // Mise à jour des autres champs
                    if (isset($postData['tentatives_echouees'])) {
                        $guide->setTentativesEchouees((int)$postData['tentatives_echouees']);
                    }
                    if (isset($postData['statut_compte'])) {
                        $guide->setStatutCompte($postData['statut_compte']);
                    }
                    if (isset($postData['date_dernier_echec'])) {
                        $guide->setDateDernierEchec(new DateTime($postData['date_dernier_echec']));
                    }

                    // Sauvegarde dans la base de données
                    if ($guideDao->maj($guide)) {
                        // Stocke une variable de confirmation dans la session
                        $_SESSION['modification_reussie'] = true;
                        // Redirige vers la page d'affichage avec les nouvelles données
                        header("Location: ?controleur=guide&methode=afficherInformation&id=$id&modification_reussie=true");
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
        // Vérifie si l'utilisateur est un administrateur ou le guide lui-même
        if (!$this->isAdmin() && (!isset($_SESSION['user_id']) || $_SESSION['user_id'] !== $id)) {
            echo "Accès non autorisé. Vous devez être administrateur ou le guide lui-même pour voir le certificat.";
            return;
        }

        try {
            $guideDao = new GuideDao($this->getPdo());
            $guide = $guideDao->find($id);

            if ($guide) {
                // Récupère le chemin du certificat en fonction de l'ID
                $cheminCertificat = $guideDao->getCheminCertificatParId($id);
                $cheminCertificatComplet = "certifications/".$cheminCertificat;

                if ($cheminCertificat && file_exists($cheminCertificatComplet)) {
                    header('Content-Type: application/pdf');
                    header('Content-Disposition: inline; filename="' . basename($cheminCertificatComplet) . '"');
                    readfile($cheminCertificatComplet);
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
        $this->breadcrumbService->buildFromRoute('guide', 'afficherPlanning');

        echo $this->getTwig()->render('planning_guide.html.twig', [
            'breadcrumb' => $this->breadcrumbService->getItems(),
        ]);
    }
     // Afficher les détails d'un guide spécifique (accessible par tous les utilisateurs)
     public function afficherInformation(int $id = null): void
     {
        $this->breadcrumbService->buildFromRoute('guide', 'afficherInformation', ['id' => $id]);

         try {
             $id = $id ?? (isset($_GET['id']) ? (int) $_GET['id'] : null);
 
             if ($id === null || $id <= 0) {
                 throw new Exception("ID invalide ou non fourni.");
             }
 
             $guideDao = new GuideDao($this->getPdo());
             $guide = $guideDao->findAssoc($id);
 
             if (!$guide) {
                 echo "Guide avec id $id pas trouvé.";
                 return;
             }
 
             $editMode = isset($_GET['editMode']) && $_GET['editMode'] === 'true';
 
             echo $this->getTwig()->render('pageInformationsGuide.html.twig', [
                 'guide' => $guide,
                 'menu' => "guide_detail",
                 'editMode' => $editMode, // Mode d'édition
                 'breadcrumb' => $this->breadcrumbService->getItems(),
             ]);
         } catch (Exception $e) {
             echo "Erreur lors de l'affichage du guide : " . $e->getMessage();
         }
     }

}
?>