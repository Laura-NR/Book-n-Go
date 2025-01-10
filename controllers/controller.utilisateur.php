<?php
require_once 'controller.class.php';
require_once 'include.php';
require_once 'validation/connexion.php';

class ControllerUtilisateur extends BaseController {
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
        global $reglesValidationConnexion;
        $this->validator = new Validator($reglesValidationConnexion);
    }

    // Affichage de la page de connexion
    public function afficherConnexion(): void {
        // Récupérer les erreurs et les données de la session (si présentes)
        $erreursInscription = $_SESSION['erreurs_connexion'] ?? [];
        $donneesInscription = $_SESSION['donnees_connexion'] ?? [];

        // Supprimer les variables de session pour éviter qu'elles ne soient affichées à nouveau
        unset($_SESSION['erreurs_connexion']);
        unset($_SESSION['donnees_connexion']);
        $template = $this->getTwig()->load('connexion_template.html.twig');

        // Affichage du template
        echo $template->render([
                'erreurs' => $erreursInscription,
                'donnees' => $donneesInscription
            ]);
    }

    // Affichage de la page d'inscription
    public function afficherInscription(): void {
        // Récupérer les erreurs et les données de la session (si présentes)
        $erreursInscription = $_SESSION['erreurs_inscription'] ?? [];
        $donneesInscription = $_SESSION['donnees_inscription'] ?? [];

        // Supprimer les variables de session pour éviter qu'elles ne soient affichées à nouveau
        unset($_SESSION['erreurs_inscription']);
        unset($_SESSION['donnees_inscription']);
        $template = $this->getTwig()->load('inscription_template.html.twig');

        // Affichage du template
        echo $template->render([
            'erreurs' => $erreursInscription,
            'donnees' => $donneesInscription
        ]);
    }

        /**
         * Vérifie si la connexion est possible en fonction des données de l'utilisateur
         *
         * @return bool true si la connexion est possible, false sinon
         */
    public function connexion(): bool
    {
        // Assure-toi que la session est démarrée en haut du fichier, avant toute sortie
        if (session_status() == PHP_SESSION_NONE) {
            session_start(); // Démarre la session si elle n'est pas déjà commencée
        }

        $email = $_POST['mail'];
        $motDePasse = $_POST['mdp'];


        if ($this->validator->valider($_POST)) {
            $utilisateurDao = new UtilisateurDao($this->getPdo());
            // Vérification de l'utilisateur
            $utilisateur = $utilisateurDao->findByEmail($email);
            if ($utilisateur) {
                if (password_verify($motDePasse, $utilisateur->getMdp())) {
                    $_SESSION['role'] = $utilisateur instanceof Guide ? 'guide' : 'voyageur';
                    $_SESSION['user_id'] = $utilisateur->getId();
                    $utilisateur->setDerniereCo(new DateTime());

                    // Effectuer la mise à jour dans la base de données selon le rôle
                    if ($utilisateur instanceof Voyageur) {
                        $voyageurDao = new VoyageurDao($this->getPdo());
                        $voyageurDao->majDerniereCo($utilisateur);
                    } else {
                        $guideDao = new GuideDao($this->getPdo());
                        $guideDao->majDerniereCo($utilisateur);
                    }

                    // Redirection après succès de la connexion
                    $this->redirect('', '', ['connexion' => true]);
                    ob_end_flush();
                    return true;
                } else {
                    // Si la vérification du mot de passe échoue, affiche un message d'erreur dans l'URL
                    $_SESSION['erreurs_connexion'][] = 'Mot de passe érroné';
                    $this->redirect('utilisateur', 'afficherConnexion', ['connexion' => false]);
                    ob_end_flush();
                    return false;
                }
            } else {
                // Si l'utilisateur n'est pas trouvé
                $_SESSION['erreurs_connexion'][] = 'Compte inexistant ou addresse érronée';
                $this->redirect('utilisateur', 'afficherConnexion', ['connexion' => false]);
                ob_end_flush();
                return false;
            }
        } else {
            $donnees = $_POST;
            $erreurs = $this->validator->getMessagesErreurs();
            //var_dump($erreurs);
            $_SESSION['erreurs_connexion'] = $erreurs;
            //var_dump($_SESSION['erreurs_commentaire']);
            $_SESSION['donnees_connexion'] = $donnees;
            $this->redirect('utilisateur', 'afficherConnexion', ['connexion' => false]);
            ob_end_flush();
            return false;
        }
    }
    


    /**
     * Méthode qui s'occupe de l'inscription d'un utilisateur, qu'il soit guide ou voyageur
     *
     * @return bool true si l'inscription est un succès, false sinon
     */
    public function inscription(): bool
    {
        $role = $_POST['profil'];
        $email = $_POST['mail'];

        //gestion de l'utilisateur deja existant dans une catégorie différente de celle pour laquelle il s'inscrit
        $utilisateurDao = new UtilisateurDao($this->getPdo());
        $utilisateurExistant = $utilisateurDao->findByEmail($email);

        //si l'utilisateur exise déjà dans la bd avec ce mail (qu'il soit guide ou voyageur) ...
        if ($utilisateurExistant) {
            // on recupère son role apres l'avoir trouvé
            $roleExistant = $utilisateurExistant instanceof Guide ? 'guide' : 'voyageur';
            if ($roleExistant !== $role) {
                // s'il est différent de celui demandé à l'inscription alors il a déjà un compte de l'autre role -> refus et redirection
                $_SESSION['erreurs_inscription'][] = 'Un compte avec cette adresse e-mail existe déjà avec un rôle différent';
                $this->redirect('utilisateur', 'afficherInscription', ['inscription' => false]);
                ob_end_flush();
                return false;
            }
        }

        // SI ON SOUHAITE INSCRIRE UN VOYAGEUR
        if ($role == "voyageur") {
            $controller = new ControllerVoyageur($this->getTwig(), $this->getLoader());
            // appeler la methode creer de voyageur qui elle ensuite effectue la validation
            if ($controller->creerVoyageur()) {
                $this->redirect('', '', ['inscription' => true]);
                ob_end_flush();
                return true;
            } else {
                $this->redirect('utilisateur', 'afficherInscription', ['inscription' => false]);
                ob_end_flush();
                return false;
            }

        // SI ON SOUHAITE INSCRIRE UN GUIDE
        } else if ($role == "guide") {
            $controller = new ControllerGuide($this->getTwig(), $this->getLoader());
            // appeler la methode creer de guide qui elle ensuite effectue la validation
            if ($controller->creerGuide()) {
                $this->redirect('', '', ['inscription' => true]);
                ob_end_flush();
                return true;
            } else {
                $this->redirect('utilisateur', 'afficherInscription', ['inscription' => false]);
                ob_end_flush();
                return false;
            }

        }
        //Sinon
        $this->redirect('utilisateur', 'afficherInscription', ['inscription' => false]);
        ob_end_flush();
        return false;

    }

    // Déconnexion de l'utilisateur
    public function deconnexion(): void {
        //session_start();
        session_unset();
        session_destroy();
        $this->redirect('', '');
        //echo "Vous avez été déconnecté.";
    }

    /**
     * Affiche le tableau de bord en fonction du rôle de l'utilisateur connecté.
     *
     * Cette méthode vérifie le rôle de l'utilisateur dans la session et affiche
     * le tableau de bord approprié en utilisant le template 'dashboard.html.twig'.
     * Actuellement, les guides et les voyageurs partagent la  même page d'accueil de tableau de bord.
     *
     * @return void
     */
    public function afficherDashboard(): void
    {
        if ($_SESSION['role'] == 'guide') {
            echo $this->getTwig()->render('dashboard.html.twig');
        } elseif ($_SESSION['role'] == 'voyageur') {
            echo $this->getTwig()->render('dashboard.html.twig');
        }
    }
}
?>
