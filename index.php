<?php
ob_start();
session_start();

require_once "include.php";

try {
    if (isset($_GET['controleur'])) {
        $controllerName = $_GET['controleur'];
    } else {
        $controllerName = '';
    }

    if (isset($_GET['methode'])) {
        $methode = $_GET['methode'];
    } else {
        $methode = '';
    }

    if ($controllerName == '' && $methode == '') {
        echo $twig->render('pageAccueil_template.html.twig');
    }
    else{
        if ($controllerName == '') {
            throw new Exception('Le controleur n\'est pas défini');
        }

        if ($methode == '') {
            throw new Exception('La méthode n\'est pas définie');
        }

        $controller = ControllerFactory::getController($controllerName, $twig, $loader);

        $controller->call($methode);
    }


} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
?>