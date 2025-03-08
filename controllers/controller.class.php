<?php

/**
 * @file controller.class.php
 * @class BaseController
 * @brief Classe de base pour les contrôleurs
 */
abstract class BaseController
{
    private PDO $pdo;
    private \Twig\Loader\FilesystemLoader $loader;
    private \Twig\Environment $twig;
    private ?array $get;
    private ?array $post;
    protected BreadcrumbService $breadcrumbService;

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

        $this->breadcrumbService = new BreadcrumbService(
            require 'config/breadcrumb_routes.php',
            // Injection de la méthode generateUrl
            function (string $controller, string $method, array $params = []) {
                return $this->generateUrl($controller, $method, $params);
            }
        );
    }


    /**
     * Appelle une méthode du contrôleur avec les paramètres appropriés.
     *
     * Cette fonction vérifie d'abord si la méthode spécifiée existe dans le contrôleur.
     * Si elle existe, elle utilise la réflexion pour récupérer la liste des paramètres
     * nécessaires à l'appel de la méthode. Si la méthode a des paramètres, elle tente
     * de les passer en vérifiant leur présence dans les paramètres GET. Si un paramètre
     * requis est manquant, une exception est levée. Si tous les paramètres requis sont
     * présents, ou s'ils sont optionnels, la méthode est appelée avec les arguments
     * appropriés. Si la méthode n'a pas de paramètres, elle est appelée directement.
     *
     * @param string $methode Le nom de la méthode à appeler.
     * @return mixed Le résultat de l'appel de la méthode.
     * @throws Exception Si la méthode n'existe pas dans le contrôleur ou si un paramètre requis est manquant.
     */
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


    /**
     * Redirige vers une autre page en construisant l'URL d'après les paramètres.
     *
     * @param string $controller Le contrôleur vers lequel rediriger.
     * @param string $method La méthode du contrôleur vers laquelle rediriger.
     * @param array $params Les paramètres à passer en GET.
     *
     * @return void
     */
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


    protected function generateUrl(string $controller, string $method, array $params = []): string {
        $url = 'index.php?controleur=' . urlencode($controller) . '&methode=' . urlencode($method);
        if (!empty($params)) {
            $url .= '&' . http_build_query($params);
        }
        return $url;
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
