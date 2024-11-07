<?php

//Ajout de l'autoload de composer
require_once 'vendor/autoload.php';

//Ajout du fichier des constantes pour configurer l'application
require_once 'config/constantes.php';

//Ajout du code pour initialiser twig
require_once 'config/twig.php';

//Ajout du modèle de connexion à la base de données
require_once 'modeles/bd.class.php';

//Ajout des modèles
require_once 'modeles/carnet_voyage.class.php';
require_once 'modeles/carnet_voyage.dao.php';

?>

