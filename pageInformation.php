<?
// inclure les fichier dont nous avons besoins
require_once 'include.php';
echo"<h1> test de la page d'informations</h1>";

// Charger et afficher le template
// je veux charger le template de la page d'accueil 
$template=$twig->load('pageInformationsGuide.html.twig');

echo $template->render();