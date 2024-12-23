<?php
// Inclusion du fichier contenant la classe de validation
require_once 'validator.class.php';

// Définition des règles que l'on souhaite vérifier pour chaque champ du formulaire
$reglesValidationConnexion = [
    'mail' => [
        'obligatoire' => true,
        'type' => 'string',
        'longueur_min' => 1,
        'longueur_max' => 255,
        'format' => FILTER_VALIDATE_EMAIL
    ],
    'mdp' => [
        'obligatoire' => true,
        'type' => 'string',
        'longueur_min' => 1,
        'longueur_max' => 255
    ],
];
// Instanciation de la classe de validation dans le controller
?>


