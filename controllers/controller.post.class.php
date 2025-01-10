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
        $posts = $postDao->findAllAssoc();

// Chargement du template pour lister les posts de voyage
        $template = $this->getTwig()->load('liste-posts.html.twig');

// Affichage du template avec posts de voyage
        echo $template->render(array(
            'posts' => $posts,
        ));
    }

    /**
     * Liste tous les posts d'un carnet spécifique
     *
     * @param int $id ID du carnet dont on veut lister les posts
     */
    public function listerParCarnet(int $id): void
    {
        $postDao = new PostDAO($this->getPdo());
        $posts = $postDao->findAllByCarnetId($id);

        $carnetDao = new CarnetVoyageDAO($this->getPdo());
        $carnet = $carnetDao->find($id);

        // Chargement du template pour lister les posts du carnet
        $template = $this->getTwig()->load('liste-posts.html.twig');

        // Affichage du template avec les posts du carnet
        echo $template->render(array(
            'posts' => $posts,
            'idCarnet' => $id,
            'idVoyageurCarnet' => $carnet->getIdVoyageur(), // A REMPLACER PAR UN GETTER APRES MDOIFICATION DE LA CLASSE CARNET
        ));
    }

/**
 * Affiche les détails d'un post spécifique ainsi que ses commentaires.
 *
 * Cette méthode récupère un post par son ID ainsi que les commentaires associés.
 * Elle gère également les erreurs de validation de commentaires stockées en session.
 * Les erreurs et les données de formulaire soumises sont passées à la vue pour pré-remplissage.
 * Si le post est trouvé, il est affiché avec ses commentaires, sinon un message d'erreur est affiché.
 *
 * @param int $id L'ID du post à afficher.
 * @return void
 */
    public function afficher($id): void
    {
        // Récupérer les erreurs et les données de la session (si présentes)
        $erreursCommentaire = $_SESSION['erreurs_commentaire'] ?? [];
        $donneesCommentaire = $_SESSION['donnees_commentaire'] ?? [];

        // Supprimer les variables de session pour éviter qu'elles ne soient affichées à nouveau
        unset($_SESSION['erreurs_commentaire']);
        unset($_SESSION['donnees_commentaire']);

        $postDao = new PostDAO($this->getPdo());
        $commentaireDao = new CommentaireDao($this->getPdo());

        // On récupère un post par son ID
        $post = $postDao->find($id);
        $commentaires = $commentaireDao->findAllWithIdPost($id);
        if ($post) {
            // Chargement du template pour afficher un post
            $template = $this->getTwig()->load('post.html.twig');
            echo $template->render(array(
                'post' => $post,
                'commentaires' => $commentaires,
                'erreursCommentaire' => $erreursCommentaire, // Passer les erreurs à la vue
                'donneesCommentaire' => $donneesCommentaire // Passer les données pour pré-remplir le formulaire
            ));
        } else {
            // Si le post n'existe pas, afficher une erreur ou rediriger
            echo "post non trouvé.";
        }
    }

    /**
     * Affiche le formulaire de création d'un post et le stocke en base de données si les données sont valides.
     *
     * Cette méthode récupère les erreurs et les données de la session (si présentes) et les passe à la vue pour pré-remplissage.
     * Si la méthode est appelée avec un POST, elle valide les données et les stocke en base de données.
     * Si les données sont valides, elle redirige vers la liste des posts du carnet.
     * Sinon, elle redirige vers la page de création de post avec les erreurs. (Validation à ajouter !)
     * @return void
     */
    public function creer(): void
    {
        $erreursPost = $_SESSION['erreurs_post'] ?? [];
        $donneesPost = $_SESSION['donnees_post'] ?? [];
        unset($_SESSION['erreurs_post']);
        unset($_SESSION['donnees_post']);

        $visiteDao = new VisiteDAO($this->getPdo());
        $visites = $visiteDao->findAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//            $validator = new Validator();
            $donnees = $_POST;

            // Validation des données
//            $validator->regle('titre', 'required|min:3');
//            $validator->regle('contenu', 'required|min:10');
//            $validator->regle('image', 'image|max:2048'); // 2MB max

            if (true/*$validator->valider($donnees)*/) {
                $postDao = new PostDAO($this->getPdo());
                $post = new Post();
                $post->setTitre($_POST['titre']);
                $post->setContenu($_POST['contenu']);
                $post->setIdCarnet($_POST['id_carnet']);
                $post->setIdVisite($_POST['id_visite']);

                // Gestion de l'upload d'image
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $nomImage = uniqid().'_'. $_FILES['image']['name'];
                    $cheminImage = 'images/post/' . $nomImage;
                    move_uploaded_file($_FILES['image']['tmp_name'], $cheminImage);
                    $post->setChemin_img($cheminImage);
                }

                $postDao->inserer($post);
                header("Location: index.php?controleur=post&methode=listerParCarnet&id=" . $_POST['id_carnet']);
                exit();
            } else {
                $_SESSION['erreurs_post'] = $validator->getMessagesErreurs();
                $_SESSION['donnees_post'] = $donnees;
                header("Location: index.php?controleur=post&methode=creer&id_carnet=" . $_POST['id_carnet']);
                exit();
            }
        } else {
            // Affichage du formulaire de création
            $template = $this->getTwig()->load('creation_post.html.twig');
            echo $template->render(array(
                'idCarnet' => $_GET['id_carnet'],
                'erreursPost' => $erreursPost,
                'donneesPost' => $donneesPost,
                'visites' => $visites
            ));
        }
    }
    /**
     * Supprime un post de la base de données.
     *
     * Cette méthode doit être appelée via un formulaire POST contenant l'ID du post à supprimer.
     * L'ID du carnet est également récupéré pour rediriger vers la liste des posts du carnet.
     * Si la méthode est appelée via un GET, elle lève une exception.
     *
     * @return void
     * @throws Exception Si la méthode est appelée via un GET.
     */
    public function supprimer(): void
    {
        if (isset($_POST["id_post"])) {
            $postDao = new PostDAO($this->getPdo());
            $idPost = $_POST["id_post"];
            $idCarnet = $_POST['id_carnet']; // Retrieve id_carnet
            $postDao->retirer($idPost);
            header("Location: index.php?controleur=post&methode=listerParCarnet&id=" . $idCarnet); // Redirect with id_carnet
            exit();
        } else {
            throw new Exception("");
        }
    }
}