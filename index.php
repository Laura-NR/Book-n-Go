<?php
ob_start();
session_start();

require_once "include.php";

$twig->addGlobal('session', $_SESSION);

try {
    $method = $_SERVER['REQUEST_METHOD'];
    $params = ($method === 'POST') ? $_POST : $_GET;

    if (isset($params['controleur'])) {
        $controllerName = $params['controleur'];
    } else {
        $controllerName = '';
    }
    if (isset($params['methode'])) {
        $methode = $params['methode'];
    } else {
        $methode = '';
    }

    if ($controllerName == '' && $methode == '') {
        echo $twig->render('pageAccueil_template.html.twig', [
            'app' => [
                'session' => $_SESSION 
            ]
        ]);
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