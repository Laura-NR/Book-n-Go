<?php
class ControllerCommentaire extends BaseController
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
//    public function lister(): void
//    {
//        $postDao = new PostDAO($this->getPdo());
//        $posts = $postDao->findAll();
//
//// Chargement du template pour lister les posts de voyage
//        $template = $this->getTwig()->load('liste-posts.html.twig');
//
//// Affichage du template avec posts de voyage
//        echo $template->render(array(
//            'posts' => $posts,
//        ));
//    }
//
//    public function listerParCarnet(int $id): void
//    {
//        $postDao = new PostDAO($this->getPdo());
//        $posts = $postDao->findAllByCarnetId($id);
//
//        // Chargement du template pour lister les posts du carnet
//        $template = $this->getTwig()->load('liste-posts.html.twig');
//
//        // Affichage du template avec les posts du carnet
//        echo $template->render(array(
//            'posts' => $posts,
//        ));
//    }

//    public function afficher($id): void
//    {
//        $postDao = new PostDAO($this->getPdo());
//        $commentaireDao = new CommentaireDao($this->getPdo());
//
//        // On récupère un post par son ID
//        $post = $postDao->find($id);
//        $commentaires = $commentaireDao->findAllWithIdPost($id);
//        if ($post) {
//            // Chargement du template pour afficher un post
//            $template = $this->getTwig()->load('post.html.twig');
//            echo $template->render(array(
//                'post' => $post,
//                'commentaires' => $commentaires,
//            ));
//        } else {
//            // Si le post n'existe pas, afficher une erreur ou rediriger
//            echo "post non trouvé.";
//        }
//    }
    public function ajouter(): void
    {
        if (isset($_POST["contenu"]) && !ctype_space($_POST["contenu"])) {
            $contenu = $_POST["contenu"];
            //faire la validation de contenu avec Validator




            // Utiliser la variable $contenu pour insérer les données dans la BD
            // en utilisant le modèle Commentaire et le DAO CommentaireDao
            $commentaire = new Commentaire();
            $commentaireDao = new CommentaireDao($this->getPdo());
            $commentaire->setDateHeurePublication(new DateTime());
            $commentaire->setContenu($contenu);

            //$idVoyageur = $_SESSION['id_voyageur'];
            //$commentaire->setIdVoyageur($idVoyageur);

            $idPost = $_POST["id_post"];
            $commentaire->setIdPost($idPost);
            $commentaireDao->inserer($commentaire);
            header("Location: index.php?controleur=post&methode=afficher&id=" . $idPost);
            exit();
        } else {
            throw new Exception("Le contenu du commentaire est vide.");
        }
    }

    public function supprimer(): void
    {
        if (isset($_POST["id_commentaire"])) {
            $commentaireDao = new CommentaireDao($this->getPdo());
            $idPost = $_POST["id_post"];
            $idCommentaire = $_POST["id_commentaire"];
            $commentaireDao->retirer($idCommentaire);
            header("Location: index.php?controleur=post&methode=afficher&id=" . $idPost);
            exit();
        } else {
            throw new Exception("");
        }
    }
}