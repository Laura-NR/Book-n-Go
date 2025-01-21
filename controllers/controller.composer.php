<?php 

class ControllerComposer extends BaseController
{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }



    /**
     * @brief Crée une nouvelle excursion.
     *
     * Cette méthode vérifie si des données ont été soumises via un formulaire.
     * Si c'est le cas, elle procédera à la création d'une excursion en utilisant les données fournies.
     *
     * @return void
     */
    public function creerExcursion(): void
    {
        if (!empty($this->getPost())) {
            
        }
    }
}