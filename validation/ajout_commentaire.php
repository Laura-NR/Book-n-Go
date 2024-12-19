<?php

// Inclusion du fichier contenant la classe de validation
require_once 'validator.class.php';

// $this->validator = new Validator([
//     'contenu' => ['obligatoire' => true, 'type' => 'string', 'longueur_min' => 2],
//     // ... autres règles
// ]);

// Définition des règles que l'on souhaite vérifier pour chaque champ du formulaire
$reglesValidation = [
    'contenu' => [
        'obligatoire' => true,
        'type' => 'string',
        'longueur_min' => 2,
        'longueur_max' => 1000
    ],
    // ... autres champs
];

// Instanciation de la classe de validation


?>
