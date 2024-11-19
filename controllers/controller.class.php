<?php

abstract class BaseController
{
    private PDO $pdo;
    private \Twig\Loader\FilesystemLoader $loader;
    private \Twig\Environment $twig;
    private ?array $get;
    private ?array $post;

    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    // Initialise la connexion à la base de données via un singleton ou une autre classe
    {
        $db = Bd::getInstance();
        $this->pdo = $db->getPdo();

        $this->loader = $loader;
        $this->twig = $twig;

        if (isset($_GET) && !empty($_GET)) {
            $this->get = $_GET;
        }

        if (isset($_POST) && !empty($_POST)) {
            $this->post = $_POST;
        }
    }

    //Pour appeler une méthode d’un contrôleur spécifique
    public function call(string $methode): mixed
    {
        //test si la methode existe
        if (!method_exists($this, $methode)) {
            throw new Exception("La methode $methode n'existe pas dans le contrôleur " . __CLASS__);
        }
        
        return $this->$methode();
    }

    // // Rendu d'une vue Twig avec les données
    // protected function render(string $template, array $data = []): void {
    //     echo $this->twig->render($template, $data);
    // }

    //Redirection vers une URL
    function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }

    public function setPdo(?PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    public function getLoader(): \Twig\Loader\FilesystemLoader
    {
        return $this->loader;
    }

    public function setLoader(\Twig\Loader\FilesystemLoader $loader): void
    {
        $this->loader = $loader;
    }

    public function getTwig(): \Twig\Environment
    {
        return $this->twig;
    }

    public function setTwig(\Twig\Environment $twig): void
    {
        $this->twig = $twig;
    }

    public function getGet(): array
    {
        return $this->get;
    }

    public function setGet(array $get): void
    {
        $this->get = $get;
    }

    public function getPost(): array
    {
        return $this->post;
    }

    public function setPost(array $post): void
    {
        $this->post = $post;
    }
}
