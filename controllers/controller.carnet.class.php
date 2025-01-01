<?php
class ControllerCarnetVoyage extends BaseController
{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
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

// Liste tous les carnets
    public function lister(): void
    {
        $carnetDao = new CarnetVoyageDAO($this->getPdo());
        $carnets = $carnetDao->findAll();

// Chargement du template pour lister les carnets de voyage
        $template = $this->getTwig()->load('carnet_voyage.html.twig');

// Affichage du template avec carnets de voyage
        echo $template->render(array(
            'carnets' => $carnets,
        ));
    }

    public function creer(): void
    {
        if (!empty($_POST)) {
            $data = [
                'titre' => $_POST['titre'],
                'chemin_img' => $_POST['chemin_img'],
                'description' => $_POST['description'],
                'id_voyageur' => $_SESSION['user_id'], // Assurez-vous que l'ID du voyageur est correctement géré
            ];

            $carnetDao = new CarnetVoyageDAO($this->getPdo());
            $nouveauCarnet = $carnetDao->inserer($data); // Assurez-vous que CarnetVoyageDAO a une méthode insert

            if ($nouveauCarnet) {
                $this->redirect('carnetVoyage', 'lister');
            } else {
                echo "Erreur lors de la création du carnet.";
            }
        } else {
            // Affichez le formulaire de création de carnet
            $template = $this->getTwig()->load('creation_carnet_dashboard.html.twig'); // Assurez-vous que le template existe
            echo $template->render();
        }
    }

    //Spécifique au dashboard
    public function listerParVoyageur(int $idVoyageur): void
    {
        $carnetDao = new CarnetVoyageDAO($this->getPdo());
        $carnets = $carnetDao->findAllByIdVoyageur($idVoyageur);

// Chargement du template pour lister les carnets de voyage
        $template = $this->getTwig()->load('liste_carnets_dashboard.html.twig');

// Affichage du template avec carnets de voyage
        echo $template->render(array(
            'carnets' => $carnets,
        ));
    }
}
//    public function afficher($id): void
//    {
//        $carnetDao = new CarnetVoyageDAO($this->getPdo());
//        // On récupère un carnet par son ID
//        $carnet = $carnetDao->find($id);
//
//        if ($carnet) {
//            // Chargement du template pour afficher un carnet
//            $template = $this->getTwig()->load('carnets_detail.html.twig');
//            echo $template->render(array(
//                'carnet' => $carnet,
//            ));
//        } else {
//            // Si le carnet n'existe pas, afficher une erreur ou rediriger
//            echo "Carnet non trouvé.";
//        }
//    }