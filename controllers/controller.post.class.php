<?php
class ControllerPost extends BaseController
{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader,)
    {
        parent::__construct($twig, $loader);
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

// Liste tous les posts
    public function lister(): void
    {
        $postDao = new PostDAO($this->getPdo());
        $posts = $postDao->findAll();

// Chargement du template pour lister les posts de voyage
        $template = $this->getTwig()->load('liste-posts.html.twig');

// Affichage du template avec posts de voyage
        echo $template->render(array(
            'posts' => $posts,
        ));
    }

    public function listerParCarnet(int $id): void
    {
        $postDao = new PostDAO($this->getPdo());
        $posts = $postDao->findAllByCarnetId($id);

        // Chargement du template pour lister les posts du carnet
        $template = $this->getTwig()->load('liste-posts.html.twig');

        // Affichage du template avec les posts du carnet
        echo $template->render(array(
            'posts' => $posts,
            'carnetId' => $id, // Optionnel, au cas où vous voulez afficher des infos sur le carnet
        ));
    }

    public function afficher($id): void
    {
        $postDao = new PostDAO($this->getPdo());
        $visiteDao = new VisiteDAO($this->getPdo());

        // On récupère un post par son ID
        $post = $postDao->find($id);
        $visite = $visiteDao->find($post->getVisite());
        //$visite = $visiteDao->find();

        if ($post) {
            // Chargement du template pour afficher un post
            $template = $this->getTwig()->load('post.html.twig');
            echo $template->render(array(
                'post' => $post,
                'visite' => $visite,

            ));
        } else {
            // Si le post n'existe pas, afficher une erreur ou rediriger
            echo "post non trouvé.";
        }
    }
}