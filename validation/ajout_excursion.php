<?php

// Inclusion du fichier contenant la classe de validation
require_once 'validator.class.php';

$reglesValidationInsertionExcursion = [
    "nom" => [
        'obligatoire' => true,
        'type' => 'string',
        'longueur_min' => 1,
        'longueur_max' => 255,
        'format' => '/^[\p{L}0-9\s.,!?\'"-]+$/u'
    ],
    "capacite" => [
        'obligatoire' => true,
        'type' => 'int',
        'longueur_min' => 1,
        'longueur_max' => 2,
        'format' => '/^[1-9][0-9]{0,1}$/',
    ],
    "chemin_image" => [
        'obligatoire' => true,
        'type_fichier' => ['image/jpg', 'image/jpeg', 'image/png'],
        'taille_max_fichier' => 5000000,
    ],
    "description" => [
        'obligatoire' => true,
        'type' => 'string',
        'longueur_min' => 1,
        'longueur_max' => 1000,
        'format' => '/^[\p{L}0-9\s.,!?\'"-]+$/u'
    ],
];