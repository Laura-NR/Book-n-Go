<?php
class ControllerCarnetVoyage extends BaseController
{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader,)
    {
        parent::__construct($loader, $twig);
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
    public function afficher($id): void
    {
        $carnetDao = new CarnetVoyageDAO($this->getPdo());
        // On récupère un carnet par son ID
        $carnet = $carnetDao->find($id);

        if ($carnet) {
            // Chargement du template pour afficher un carnet
            $template = $this->getTwig()->load('carnet_details.html.twig');
            echo $template->render(array(
                'carnet' => $carnet,
            ));
        } else {
            // Si le carnet n'existe pas, afficher une erreur ou rediriger
            echo "Carnet non trouvé.";
        }
    }
}