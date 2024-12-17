<?php
class ControllerUtilisateur{
    private $utilisateurDao;
    private $twig;

    public function __construct(\Twig\Environment $twig, UtilisateurDao $utilisateurDao) {
        $this->twig = $twig;
        $this->utilisateurDao = $utilisateurDao;
    }

    // Affichage de la page de connexion
    public function afficherConnexion(): void {
        echo $this->twig->render('connexion_template.html.twig');
    }

    // Affichage de la page d'inscription
    public function afficherInscription(): void {
        echo $this->twig->render('inscription_template.html.twig');
    }

    // Connexion de l'utilisateur
    public function connexion(): void {
        session_start();

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $motDePasse = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        if (!$email || !$motDePasse) {
            echo "Erreur : Veuillez remplir tous les champs.";
            return;
        }

        // Vérification de l'utilisateur
        $utilisateur = $this->utilisateurDao->findByEmail($email);
        if ($utilisateur && password_verify($motDePasse, $utilisateur->getMdp())) {
            $role = $utilisateur instanceof Guide ? 'guide' : 'voyageur';
            $_SESSION['user_id'] = $utilisateur->getId();
            $_SESSION['role'] = $role;

            echo "Connexion réussie ! Rôle : " . ucfirst($role);
        } else {
            echo "Erreur : Email ou mot de passe incorrect.";
        }
    }

    // Inscription d'un utilisateur
    public function inscription(): void {
        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
        $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $motDePasse = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $numeroTel = filter_input(INPUT_POST, 'numeroTel', FILTER_SANITIZE_STRING);
        $cheminCertification = isset($_FILES['certification']) ? $_FILES['certification']['tmp_name'] : null;

        if (!$nom || !$prenom || !$email || !$motDePasse || !$numeroTel) {
            echo "Erreur : Veuillez remplir tous les champs.";
            return;
        }

        $motDePasseHash = password_hash($motDePasse, PASSWORD_BCRYPT);

        if ($cheminCertification) {
            // Création d'un guide
            $utilisateur = new Guide(null, $nom, $prenom, $numeroTel, $email, $motDePasseHash, $cheminCertification);
        } else {
            // Création d'un voyageur
            $utilisateur = new Voyageur(null, $nom, $prenom, $numeroTel, $email, $motDePasseHash);
        }

        if ($this->utilisateurDao->creer($utilisateur)) {
            echo "Inscription réussie ! Connectez-vous maintenant.";
        } else {
            echo "Erreur : Une erreur est survenue lors de l'inscription.";
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
