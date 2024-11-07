<?
// inclure les fichier dont nous avons besoins
require_once 'include.php';
echo"<h1> test de la page d'accueil";
// Préparer les variables spécifiques à la page
$variable = [
    'description' => 'Bienvenue sur Book-n-Go, votre compagnon de voyage.',//dans le template base c le meta name
];

// Charger et afficher le template
// je veux charger le template de la page d'accueil 
$template=$twig->load('pageAccueil_template.html.twig');

echo $template->render($variable);