<?php
require_once "include.php";

try {
    if (isset($_GET['controlleur'])) {
        $controllerName = $_GET['controlleur'];
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

    $controller->callMethode($methode);
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

try {

    $sql ="SELECT * FROM visite";
        $newVisite = new VisiteDao();
        $pdoStatement = $newVisite->getPdo()->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $visite = $pdoStatement->fetchAll();

    var_dump($visite);
}
catch(Exception $e2){} */

?>