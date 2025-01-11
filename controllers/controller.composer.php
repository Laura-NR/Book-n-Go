<?php 
// Classe ...
class ControllerComposer extends BaseController
{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    public function creerExcursion(): void
    {
        if (!empty($this->getPost())) {
            
        }
    }
}