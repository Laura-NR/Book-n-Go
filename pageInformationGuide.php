<?
// inclure les fichier dont nous avons besoins
require_once 'include.php';
echo"<h1> test de la page d'informations des guides</h1>";

// Charger et afficher le template
// je veux charger le template de la page d'accueil 
/* $template=$twig->load('pageInformationsGuide.html.twig');

echo $template->render(); */

// Test pour appeler la méthode directement après chargement du template
$controller = new ControllerGuide($twig, $loader);

$controller->afficher(1); 