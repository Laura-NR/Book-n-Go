<?php
require_once 'controller.class.php';

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
    public function connexion(): void {
        //session_start();
        var_dump($_POST);
        $email = $_POST['username'];
        $motDePasse = $_POST['mdp'];

        $utilisateurDao = new UtilisateurDao($this->getPdo());
        // Vérification de l'utilisateur
        $utilisateur = $utilisateurDao->findByEmail($email);
        if ($utilisateur && password_verify($motDePasse, $utilisateur->getMdp())) {
            $_SESSION['role'] = $utilisateur instanceof Guide ? 'guide' : 'voyageur';
            $_SESSION['user_id'] = $utilisateur->getId();
            //$_SESSION['role'] = $role;
            var_dump($_SESSION);

            echo "Connexion réussie ! Rôle : " . $_SESSION['role'];
        } else {
            echo "Erreur : Email ou mot de passe incorrect.";
        }
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

        if ($role === "voyageur"){
            // appeler la methode creer de voyageur 
            // attention mettre le mdp hacher 
           // $utilisateur = new Voyageur($nom, $prenom, $numeroTel, $email, $motDePasse);
           $utilisateur = new Voyageur();
            $utilisateur->creerVoyageur();
        }
        else{
            // appeler la methode creer de guide
            // attention mettre le mdp hacher 
            $utilisateur = new Guide(); 
            $utilisateur->creerGuide();
        }

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
