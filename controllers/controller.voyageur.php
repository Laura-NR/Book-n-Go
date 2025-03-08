<?php
require_once 'controller.class.php';
require_once 'validation/ajout_voyageur.php';

/**
 * @file controller.voyageur.php
 * @class ControllerVoyageur
 * @brief Classe du contrôleur pour la gestion des voyageurs
 */
class ControllerVoyageur extends BaseController {

    /**
     * @var Validator
     */
    private Validator $validator; // Instance de la classe Validator
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


    /**
     * Crée un voyageur avec les données fournies
     * @return bool True si le voyageur est créé, sinon false
     */
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
                $voyageur->setTentativesEchouees(0); // Tentatives échouées initialisées à 0
                $voyageur->setDateDernierEchec(null); // Aucun échec au départ
                $voyageur->setStatutCompte('actif'); // Le compte est actif par défaut
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

    /**
     * Supprime un voyageur de la base de données.
     *
     * Cette méthode supprime un voyageur à partir de son ID, après vérification de l'existence du voyageur
     * et de la soumission du formulaire de suppression. Si la suppression réussit, elle redirige vers la page d'accueil.
     *
     * @param int $id L'identifiant du voyageur à supprimer.
     * @return void
     */
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

    /**
     * Modifie un voyageur de la base de données.
     *
     * Cette méthode modifie un voyageur existant en validant les données du formulaire
     * et en appelant la méthode `modify` de VoyageurDao.
     * Si la modification est réussie, elle redirige vers la page d'affichage normale avec un paramètre de confirmation
     * Sinon, elle affiche le formulaire de modification avec les erreurs.
     *
     * @param int $id L'identifiant du voyageur à modifier.
     * @return void
     */
    public function modifierVoyageur(int $id): void
    {
        try {
            $voyageurDao = new VoyageurDao($this->getPdo());
            $voyageur = $voyageurDao->find($id);

            if (!$voyageur) {
                echo "Erreur : voyageur non trouvé.";
                return;
            }

            // Vérification de la soumission du formulaire de modification
            if (isset($_POST['action']) && $_POST['action'] === 'modifier') {
                $postData = $this->getPost();

                if (!empty($postData)) {
                    // Mise à jour des données du voyageur
                    if (isset($postData['nom'])) $voyageur->setNom($postData['nom']);
                    if (isset($postData['prenom'])) $voyageur->setPrenom($postData['prenom']);
                    if (isset($postData['numero_tel'])) $voyageur->setNumeroTel($postData['numero_tel']);
                    if (isset($postData['mail'])) $voyageur->setMail($postData['mail']);
                    if (isset($postData['tentatives_echouees'])) $voyageur->setTentativesEchouees($postData['tentatives_echouees']);
                    if (isset($postData['statut_compte'])) $voyageur->setStatutCompte($postData['statut_compte']);
                    if (isset($postData['date_dernier_echec'])) $voyageur->setDateDernierEchec(new DateTime($postData['date_dernier_echec']));

                    // Sauvegarde dans la base de données
                    if ($voyageurDao->mettreAJour($voyageur)) {
                        $_SESSION['modification_reussie'] = true;
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

    /**
     * Lister tous les voyageurs.
     *
     * Cette méthode charge le template voyageurList.twig et l'affiche avec les données des voyageurs.
     * Si une erreur survient, un message d'erreur est affiché.
     *
     * @return void
     */
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


    /**
     * Affiche la page d'un voyageur.
     *
     * Cette méthode charge le template pageInformationsVoyageur.html.twig et l'affiche avec les données du voyageur.
     * Si une erreur survient, un message d'erreur est affiché.
     *
     * @param int $id L'identifiant du voyageur dont afficher la page.
     * @return void
     */
    public function afficher(int $id): void
    {
        $this->breadcrumbService->buildFromRoute('voyageur', 'afficher', ['id' => $id]);

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
                'breadcrumb' => $this->breadcrumbService->getItems()
            ]);
        } catch (Exception $e) {
            echo "Erreur lors de l'affichage du voyageur : " . $e->getMessage();
        }
    }



}
?>
