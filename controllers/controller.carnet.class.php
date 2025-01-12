<?php
/**
 * @class ControllerCarnetVoyage
 * @brief Classe de contrôleur pour les carnets de voyage
 */
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


    /**
     * Liste tous les carnets de voyage.
     *
     * Cette méthode charge le template "carnet_voyage.html.twig" et l'affiche avec les données des carnets de voyage.
     * Les carnets sont récupérés via la méthode "findAll" de la classe "CarnetVoyageDAO".
     *
     * @return void
     */
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



    /**
     * Crée un nouveau carnet de voyage.
     *
     * Cette méthode traite le formulaire de création de carnet et insert le carnet dans la base de données.
     * Si le formulaire est envoyé avec des données valides, le carnet est ajouté via la méthode "inserer" de la classe "CarnetVoyageDAO".
     * Si le formulaire n'est pas envoyé, le formulaire de création de carnet est affiché.
     *
     * @return void
     */
    public function creer(): void
    {
        if (!empty($_POST)) {
            $data = [
                'titre' => $_POST['titre'],
                'image_carnet' => $_FILES['image_carnet'],
                'description' => $_POST['description'],
                'id_voyageur' => $_SESSION['user_id'],
            ];

            //traitement de l'image afin de la stocker
            $repertoire = './images/carnet/';
            $nomImage = basename($_FILES['image_carnet']['name']);
            $cible = $repertoire . $nomImage;

            if(move_uploaded_file($_FILES['image_carnet']['tmp_name'], $cible)) {
                $data['chemin_img'] = $cible;
            }

            // Insertion du carnet dans la base de données
            $carnetDao = new CarnetVoyageDAO($this->getPdo());
            $nouveauCarnet = $carnetDao->inserer($data);

            if ($nouveauCarnet) {
                $this->redirect('carnetVoyage', 'lister');
            } else {
                echo "Erreur lors de la création du carnet.";
            }
        } else {
            // Afficher le formulaire de création de carnet. Retour d'erreurs à ajouter
            $template = $this->getTwig()->load('creation_carnet_dashboard.html.twig');
            echo $template->render();
        }
    }


    /**
     * Lister les carnets de voyage appartenant à un voyageur.
     *
     * Cette méthode charge le template "liste_carnets_dashboard.html.twig" et l'affiche avec les carnets de voyage correspondant au voyageur d'ID $idVoyageur.
     * Les carnets sont récupérés via la méthode "findAllByIdVoyageur" de la classe "CarnetVoyageDAO".
     *
     * @param int $idVoyageur ID du voyageur
     * @return void
     */
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