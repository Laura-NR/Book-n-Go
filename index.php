<?php
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
        $controllerName = 'excursion';
        $methode = 'recupererVisites';
    }

    if ($controllerName == '') {
        throw new Exception('Le controleur n\'est pas défini');
    }

    if ($methode == '') {
        throw new Exception('La méthode n\'est pas définie');
    }

    $controller = ControllerFactory::getController($controllerName, $twig, $loader);

    $controller->call($methode);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

/* try {
    // Get the instance of the singleton database connection
    $db = bd::getInstance();

    // Get the PDO object from the instance
    $pdo = $db->getPdo();

    // Try to execute a simple query
    $stmt = $pdo->query('SELECT 1');
    if ($stmt) {
        echo 'Database connection is successful.';
    } else {
        echo 'Database connection failed.';
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}


try  {
    if (isset($_GET['controleur'])){
        $controllerName=$_GET['controleur'];
    }else{
        $controllerName='';
    }

    if (isset($_GET['methode'])){
        $methode=$_GET['methode'];
    }else{
        $methode='';
    }

    //Gestion de la page d'accueil par défaut
    // POUR L'INSTANT PAR DEFAUT ON AFFICHE LA LISTE DES CARNETS DE VOYAGE --- A CHANGER
    if ($controllerName == '' && $methode ==''){
        $controllerName='carnetVoyage';
        $methode='lister';
    }

    if ($controllerName == '' ){
        throw new Exception('Le controleur n\'est pas défini');
    }

    if ($methode == '' ){
        throw new Exception('La méthode n\'est pas définie');
    }

    $controller="Controller".ucfirst($controllerName);
    $controllerNEW = new $controller($twig, $loader);
    $controllerNEW->call($methode);

}

catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
*/
?>