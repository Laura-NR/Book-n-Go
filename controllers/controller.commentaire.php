<?php

require_once 'validation/ajout_commentaire.php';

/**
 * @class ControllerCommentaire
 * @brief Classe du contrôleur pour la gestion des commentaires
 */
class ControllerCommentaire extends BaseController
{
    /**
     * @var Validator
     */
    private Validator $validator; // Instance de la classe Validator
    
    //Contructeur du contrôleur de commentaire, initialise les objets Twig indispensables pour la gestion des templates
    // et instancie la classe de validation
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader,)
    {
        parent::__construct($twig, $loader);
        global $reglesValidation;
        $this->validator = new Validator($reglesValidation);
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



    /**
     * Ajoute un commentaire pour un post spécifique
     *
     * Cette méthode gère la validation des données de formulaire pour l'ajout d'un commentaire.
     * Si les données sont valides, elle les stocke en base de données et redirige vers la page de détail du post.
     * Si les données sont invalides, elle stocke les erreurs en session et redirige vers la page de détail du post.
     * Si l'utilisateur n'est pas connecté, elle gère l'erreur.
     *
     * @return void
     */
    public function ajouter(): void
    {
        if (isset($_POST["contenu"]) && !ctype_space($_POST["contenu"])) {
            if (!isset($_SESSION["user_id"]) or $_SESSION["role"] != "voyageur") {
                // gérer l'erreur au cas où utilisateur n'est pas connecté
            } else {
                $donnees = $_POST;
                $idPost = $_POST["id_post"];

                //faire la validation de contenu avec Validator
                if ($this->validator->valider($donnees)) {
                    // Traitement des données valides (envoi au modèle, etc.)
                    $contenu = $_POST["contenu"];
                    // Utiliser la variable $contenu pour insérer les données dans la BD
                    // en utilisant le modèle Commentaire et le DAO CommentaireDao

                    $commentaire = new Commentaire();
                    $commentaireDao = new CommentaireDao($this->getPdo());

                    // ajout de la date-heure actuelle au commentaire
                    $commentaire->setDateHeurePublication(new DateTime());
                    // ajout du contenu du commentaire
                    $commentaire->setContenu($contenu);

                    $idVoyageur = $_SESSION['user_id'];
                    $commentaire->setIdVoyageur($idVoyageur);

                    $commentaire->setIdPost($idPost);
                    $commentaireDao->inserer($commentaire);

                    // Redirection après traitement réussi
                    header("Location: index.php?controleur=post&methode=afficher&id=" . $idPost);
                    exit();
                } else {
                    $erreurs = $this->validator->getMessagesErreurs();

                    $_SESSION['erreurs_commentaire'] = $erreurs;

                    $_SESSION['donnees_commentaire'] = $donnees;
                    // Rediriger pour éviter le double traitement du formulaire en cas de rafraîchissement de la page
                    header("Location: index.php?controleur=post&methode=afficher&id=" . $idPost);
                }
            }
        }
    }


    /**
     * Supprime un commentaire spécifique
     *
     * Cette méthode gère la suppression d'un commentaire spécifique.
     * Si le commentaire existe, elle le supprime en base de données et redirige vers la page de détail du post.
     * Si le commentaire n'existe pas, elle gère l'erreur.
     *
     * @return void
     * @throws Exception Si le commentaire n'existe pas
     */
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
            throw new Exception("!");
        }
    }
}