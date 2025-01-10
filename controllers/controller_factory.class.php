<?php

class ControllerFactory
{

    /**
     * Crée un objet de type Controller en fonction du parametre controleur
     *
     * @param string $controleur Le nom du controleur créé
     * @param \Twig\Environment $twig L'environnement Twig
     * @param \Twig\Loader\FilesystemLoader $loader Le chargeur de fichiers Twig
     * @return mixed Un objet de type Controller
     * @throws Exception Si le controleur n'existe pas
     */
    public static function getController($controleur, \Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        $controllerName="Controller".ucfirst($controleur);
        if (!class_exists($controllerName)) {
            throw new Exception("Le controlleur $controllerName n'existe pas");
        }
        return new $controllerName($twig, $loader);

    }
}