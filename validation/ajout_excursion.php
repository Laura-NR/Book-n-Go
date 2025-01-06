<?php

// Inclusion du fichier contenant la classe de validation
require_once 'validator.class.php';

$reglesValidationInsertionExcursion = [
    "titre" => [
        'obligatoire' => true,
        'type' => 'string',
        'longueur_min' => 1,
        'longueur_max' => 255,
        'format' => '/^[a-zA-ZÀ-ÿ\'\s-]+$/'
    ],
    "capacite" => [
        'obligatoire' => true,
        'type' => 'int',
        'longueur_min' => 1,
        'longueur_max' => 2,
        'format' => '/^[1-9][0-9]{0,1}$/',
    ],
    "image" => [
        'obligatoire' => true,
        'type_fichier' => ['image/jpeg', 'image/png'],
        'taille_max_fichier' => 5000000,
    ],
    "description" => [
        'obligatoire' => true,
        'type' => 'string',
        'longueur_min' => 1,
        'longueur_max' => 1000,
        'format' => '/^[a-zA-ZÀ-ÿ\'\s-]+$/'
    ],
];