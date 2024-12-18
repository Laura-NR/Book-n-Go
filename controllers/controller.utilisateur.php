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
        //session_start();
        //var_dump($_POST);
        $email = $_POST['username'];
        $motDePasse = $_POST['mdp'];

        $utilisateurDao = new UtilisateurDao($this->getPdo());
        // Vérification de l'utilisateur
        $utilisateur = $utilisateurDao->findByEmail($email);
        if ($utilisateur){
            echo "Utilisateur existant";
            if(password_verify($motDePasse, $utilisateur->getMdp())) {
                echo "Mot de passe OK";
            $_SESSION['role'] = $utilisateur instanceof Guide ? 'guide' : 'voyageur';
            $_SESSION['user_id'] = $utilisateur->getId();
            $utilisateur->setDerniereCo(new DateTime());
                if($utilisateur instanceof Voyageur){
                    $voyageurDao = new VoyageurDao($this->getPdo());
                    $voyageurDao->majDerniereCo($utilisateur);

                }
                else{
                    $guideDao = new GuideDao($this->getPdo());
                    $guideDao->majDerniereCo($utilisateur);
                    }
                    //$_SESSION['role'] = $role;
                    var_dump($utilisateur->getDerniereCo());

                //echo "Connexion réussie ! Rôle : " . $_SESSION['role'];
                // Redirige vers la page d'accueil après suppression

            }
        else{
            //echo "Erreur : Email ou mot de passe incorrect.";
            return false;
        }

        }
        else {
            //echo "Erreur : Email ou mot de passe incorrect.";
            return false;
        }
        return true;

    }

    // Inscription d'un utilisateur
    public function inscription(): void {

        var_dump($_POST);

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
        }
        else if ($role == "guide"){
            // appeler la methode creer de guide
            // attention mettre le mdp hacher
            $controller = new ControllerGuide($this->getTwig(), $this->getLoader());
            $controller->creerGuide();

        }
        else{
            exit;
        }
        header("Location: /index.php");
        exit;

    }

    // Déconnexion de l'utilisateur
    public function deconnexion(): void {
        session_start();
        session_unset();
        session_destroy();
        echo "Vous avez été déconnecté.";
    }
}
?>
