<?php

class ControllerFactory
{
    public static function getController($controlleur, \Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        $controllerName="Controller".ucfirst($controlleur);
        if (!class_exists($controllerName)) {
            throw new Exception("Le controlleur $controllerName n'existe pas");
        }
        return new $controllerName($twig, $loader);

    }
}