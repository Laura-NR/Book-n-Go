<?php

// Inclusion du fichier contenant la classe de validation
require_once 'validator.class.php';

$reglesValidationInsertionVisite = [
    "titre" => [
        'obligatoire' => true,
        'type' => 'string',
        'longueur_min' => 1,
        'longueur_max' => 255,
        'format' => '/^[a-zA-ZÀ-ÿ\'\s-]+$/'
    ],
    "adresse" => [
        'obligatoire' => true,
        'type' => 'string',
        'longueur_min' => 1,
        'longueur_max' => 255,
        'format' => '/^[0-9]{1,3}\s[a-zA-ZÀ-ÿ\'\s-]+$/'

    ],
    "ville" => [
        'obligatoire' => true,
        'type' => 'string',
        'longueur_min' => 1,
        'longueur_max' => 255,
        'format' => '/^[a-zA-ZÀ-ÿ\'\s-]+$/'
    ],
    "codePostal" => [
        'obligatoire' => true,
        'type' => 'int',
        'longueur_min' => 5,
        'longueur_max' => 5,
        'format' => '/^[0-9]{5}$/'
    ],
    "description" => [
        'obligatoire' => false,
        'type' => 'string',
        'longueur_min' => 0,
        'longueur_max' => 255,
    ]
];