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

    // Pour appeler une méthode d’un contrôleur spécifique
    public function call(string $methode): mixed
    {
        // Vérifier si la méthode existe dans le contrôleur
        if (!method_exists($this, $methode)) {
            throw new Exception("La méthode $methode n'existe pas dans le contrôleur " . __CLASS__);
        }

        // Récupérer la liste des paramètres de la méthode
        $reflectionMethod = new ReflectionMethod($this, $methode);
        $parameters = $reflectionMethod->getParameters();

        // Si la méthode a des paramètres, on essaie de les passer
        if (count($parameters) > 0) {
            $args = [];
            foreach ($parameters as $param) {
                $paramName = $param->getName();
                if (isset($_GET[$paramName])) {
                    $args[] = $_GET[$paramName];
                } else {
                    // Si un paramètre requis est absent, une exception est levée
                    if (!$param->isOptional()) {
                        throw new Exception("Paramètre manquant : $paramName pour la méthode $methode");
                    }
                    // Si le paramètre est optionnel, utiliser sa valeur par défaut
                    $args[] = $param->getDefaultValue();
                }
            }
            return $this->$methode(...$args); // Appel avec les arguments
        }

        // Si la méthode n'a pas de paramètres, on l'appelle directement
        return $this->$methode();
    }


    // // Rendu d'une vue Twig avec les données
    // protected function render(string $template, array $data = []): void {
    //     echo $this->twig->render($template, $data);
    // }

    //Redirection vers une URL
    function redirect(string $controller, string $method, array $params = []): void
    {
        // Construction de l'URL de base
        $url = 'index.php?controleur=' . urlencode($controller) . '&methode=' . urlencode($method);
        // Ajout des paramètres
        if (!empty($params)) {
            $url .= '&' . http_build_query($params);
        }
        // Redirection à l'URL qui a étéée contruite
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
