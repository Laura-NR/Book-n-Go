<?php
require_once 'controller.class.php';

class ControllerLogin extends BaseController
{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    public function login(): void
    {
        if (empty($this->getPost()) ||
            !isset($this->getPost()['mail'], $this->getPost()['mdp'])) {
            $this->setFlashMessage('error', 'Données de connexion manquantes.');
            $this->redirect('utilisateur', 'login');
            return;
        }

        try {
            $mail = $this->getPost()['mail'];
            $mdp = $this->getPost()['mdp'];

            $userDao = new UserDao($this->getPdo());
            $user = $userDao->findByMail($mail);

            if ($user && password_verify($mdp, $user->getMdp())) {
                $_SESSION['role'] = $user->getRole();
                $this->redirect('dashboard');
            } else {
                $this->setFlashMessage('error', "Connexion échouée : Email ou mot de passe incorrect.");
                $this->redirect('utilisateur', 'login');
            }
        } catch (Exception $e) {
            $this->setFlashMessage('error', "Erreur lors de la connexion : " . $e->getMessage());
            $this->redirect('utilisateur', 'login');
        }
    }

    public function register(): void
    {
        if (empty($this->getPost()) ||
            !isset($this->getPost()['nom'], $this->getPost()['prenom'], $this->getPost()['numero_tel'], $this->getPost()['mail'], $this->getPost()['mdp'], $this->getPost()['role'])) {
            $this->setFlashMessage('error', "Données de l'inscription manquantes.");
            $this->redirect('utilisateur', 'register');
            return;
        }

        try {
            $nom = $this->getPost()['nom'];
            $prenom = $this->getPost()['prenom'];
            $numero_tel = $this->getPost()['numero_tel'];
            $mail = $this->getPost()['mail'];
            $mdp = password_hash($this->getPost()['mdp'], PASSWORD_BCRYPT);
            $role = $this->getPost()['role'];

            if ($role === 'guide') {
                $user = new Guide($nom, $prenom, $numero_tel, $mail, $mdp);
                $userDao = new GuideDao($this->getPdo());
            } elseif ($role === 'voyageur') {
                $user = new Voyageur($nom, $prenom, $numero_tel, $mail, $mdp);
                $userDao = new VoyageurDao($this->getPdo());
            } else {
                $this->setFlashMessage('error', "Le rôle doit être 'guide' ou 'voyageur'.");
                $this->redirect('utilisateur', 'register');
                return;
            }

            if ($userDao->creer($user)) {
                $this->setFlashMessage('success', $role === 'guide' ? "Guide inscrit avec succès." : "Voyageur inscrit avec succès.");
                $this->redirect('utilisateur', 'login');
            } else {
                $this->setFlashMessage('error', "Erreur lors de l'inscription.");
                $this->redirect('utilisateur', 'register');
            }
        } catch (Exception $e) {
            $this->setFlashMessage('error', "Erreur lors de l'inscription : " . $e->getMessage());
            $this->redirect('utilisateur', 'register');
        }
    }

    public function deconnexion(): void
    {
        session_start();
        session_unset();
        session_destroy();
        $this->redirect('utilisateur', 'login');
    }

    private function setFlashMessage(string $type, string $message): void
    {
        $_SESSION["{$type}_message"] = $message;
    }

    private function redirect(string $controller, string $action = ''): void
    {
        $url = "index.php?controleur={$controller}";
        if ($action) {
            $url .= "&action={$action}";
        }
        header("Location: {$url}");
        exit();
    }
}
?>
