<?php

require_once "include.php";

try {
    // Si les paramètres 'controleur' et 'methode' sont vides, afficher la page d'accueil par défaut
    if (empty($_GET['controleur']) || empty($_GET['methode'])) {
        echo $twig->render('inscription_template.html.twig');
        exit;
    }

    $controllerName = $_GET['controleur'] ?? '';
    $methode = $_GET['methode'] ?? '';

    // Vérifier si les paramètres sont valides
    if (!$controllerName) {
        throw new Exception('Le contrôleur est manquant.');
    }

    if (!$methode) {
        throw new Exception('La méthode est manquante.');
    }

    // Instancier le contrôleur via le Factory
    $controller = ControllerFactory::getController($controllerName, $twig, $loader);
    $controller->call($methode);

} catch (Exception $e) {
    // Afficher une page d'erreur personnalisée
    echo $twig->render('error_template.html.twig', ['error' => $e->getMessage()]);
}
