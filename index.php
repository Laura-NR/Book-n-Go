<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['role'])) {
        $_SESSION['role'] = "visiteur";
}
require_once "include.php";

$twig->addGlobal('session', $_SESSION);

try {
    $method = $_SERVER['REQUEST_METHOD'];
    $params = array_merge($_GET, $_POST);

    if (isset($params['controleur'])) {
        $controllerName = htmlspecialchars($params['controleur'], ENT_QUOTES, 'UTF-8');
    } else {
        $controllerName = '';
    }
    if (isset($params['methode'])) {
        $methode = htmlspecialchars($params['methode'], ENT_QUOTES, 'UTF-8');
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