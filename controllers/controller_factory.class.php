<?php

class ControllerFactory
{
    public static function getController($controller, \Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        $controllerName="Controller".ucfirst($controller);
        if (!class_exists($controllerName)) {
            throw new Exception("Le controlleur $controllerName n'existe pas");
        }
        return new $controllerName($twig, $loader);

    }
}