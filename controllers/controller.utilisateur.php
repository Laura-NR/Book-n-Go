<?php
require_once 'controller.class.php';
require_once 'include.php';

class ControllerUtilisateur extends BaseController {
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
    }

    // Affichage de la page de connexion
    public function afficherConnexion(): void {
        $template = $this->getTwig()->load('connexion_template.html.twig');

        // Affichage du template
        echo $template->render();
    }

    // Affichage de la page d'inscription
    public function afficherInscription(): void {
        $template = $this->getTwig()->load('inscription_template.html.twig');

        // Affichage du template
        echo $template->render();
    }

    // Connexion de l'utilisateur
    public function connexion(): bool {
        // Assure-toi que la session est démarrée en haut du fichier, avant toute sortie
        if (session_status() == PHP_SESSION_NONE) {
            session_start(); // Démarre la session si elle n'est pas déjà commencée
        }
    
        $email = $_POST['username'];
        $motDePasse = $_POST['mdp'];
    
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
                $this->redirect('utilisateur', 'connexion', ['connexion' => false]);
                ob_end_flush();
                return false;
            }
        } else {
            // Si l'utilisateur n'est pas trouvé
            $this->redirect('utilisateur', 'connexion', ['connexion' => false]);
            ob_end_flush();
            return false;
            
        }
    }
    

    // Inscription d'un utilisateur
    public function inscription(): bool {

       // var_dump($_POST);

        // $nom = $_POST['nom'];
        // $prenom = $_POST['prenom'];
        // $numeroTel = $_POST['numeroTel'];
        // $email = $_POST['mail'];
        // $motDePasse = $_POST['mdp'];
        $role = $_POST['profil'];
        // $certif = $POST['certification'];

        if ($role == "voyageur"){
            // appeler la methode creer de voyageur 
            // attention mettre le mdp hacher 
           // $utilisateur = new Voyageur($nom, $prenom, $numeroTel, $email, $motDePasse);
           //$utilisateur = new Voyageur();
            //$utilisateur->creerVoyageur();
            $controller = new ControllerVoyageur($this->getTwig(), $this->getLoader());
            $controller->creerVoyageur();

            $this->redirect('', '', ['inscription' => true]);
            ob_end_flush();
            return true;
        }
        else if ($role == "guide"){
            // appeler la methode creer de guide
            // attention mettre le mdp hacher
            $controller = new ControllerGuide($this->getTwig(), $this->getLoader());
            $controller->creerGuide();

            $this->redirect('', '', ['inscription' => true]);
            ob_end_flush();
            return true;

        }
        else{
            $this->redirect('utilisateur', 'inscription', ['inscription' => false]);
            ob_end_flush();
            return false;
            exit;
        }
    }

    // Déconnexion de l'utilisateur
    public function deconnexion(): void {
        //session_start();
        session_unset();
        session_destroy();
        echo "Vous avez été déconnecté.";
    }

    public function afficherDashboard(): void
    {
        if ($_SESSION['role'] == 'guide') {
            echo $this->getTwig()->render('dashboard.html.twig');
        } elseif ($_SESSION['role'] == 'voyageur') {
            echo $this->getTwig()->render('page_voyageur.html.twig');
        }
    }
}
?>
