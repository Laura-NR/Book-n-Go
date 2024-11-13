<?php

class ControllerGuide extends Controller {
    public function __construct(\Twig\Loader\FilesystemLoader $loader, \Twig\Environment $twig) {
        parent::__construct($loader, $twig);
    }

    // Liste tous les guides
    public function lister(): void {
        $guideDao = new GuideDao($this->getPdo());
        $guides = $guideDao->findAll();

        // Chargement du template pour lister les guides
        $template = $this->getTwig()->load('guideList.twig');
        
        // Affichage du template avec les données des guides
        echo $template->render([
            'guides' => $guides,
            'menu' => "guide"
        ]);
    }

    // Affiche les détails d'un guide spécifique
    public function afficher(int $id): void {
        $guideDao = new GuideDao($this->getPdo());
        $guide = $guideDao->find($id);

        // Chargement du template pour afficher les détails d'un guide
        $template = $this->getTwig()->load('guideDetail.twig');
        
        // Affichage du template avec les données du guide
        echo $template->render([
            'guide' => $guide,
            'menu' => "guide_detail"
        ]);
    }
}
?>
