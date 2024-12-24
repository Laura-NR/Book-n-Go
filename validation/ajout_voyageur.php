<?php
// Inclusion du fichier contenant la classe de validation
require_once 'validator.class.php';

// Définition des règles que l'on souhaite vérifier pour chaque champ du formulaire
$reglesValidationInscriptionVoyageur = [
'nom' => [
    'obligatoire' => true,
    'type' => 'string',
    'longueur_min' => 1,
    'longueur_max' => 255,
    'format' => '/^[a-zA-ZÀ-ÿ\'-]+$/'
    ],
'prenom' => [
    'obligatoire' => true,
    'type' => 'string',
    'longueur_min' => 1,
    'longueur_max' => 255,
    'format' => '/^[a-zA-ZÀ-ÿ\'-]+$/'
    ],
'mail' => [
    'obligatoire' => true,
    'type' => 'string',
    'longueur_min' => 5,
    'longueur_max' => 255,
    'format' => FILTER_VALIDATE_EMAIL
    ],
'numero_tel' => [
    'obligatoire' => true,
    'type' => 'string',
    'longueur_min' => 10,
    'longueur_max' => 10,
    'format' => '/^[0]{1}[0-9]{9}/'
    ],
'mdp' => [
    'obligatoire' => true,
    'type' => 'string',
    'longueur_min' => 16,
    'longueur_max' => 32
    ],
];

// Instanciation de la classe de validation dans le controller

?>