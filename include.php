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
require_once 'modeles/commentaire.dao.php';
require_once 'modeles/commentaire.class.php';
require_once 'modeles/excursion.class.php';
require_once 'modeles/excursion.dao.php';
require_once 'modeles/guide.class.php';
require_once 'modeles/guide.dao.php';
require_once 'modeles/composer.class.php';
require_once 'modeles/composer.dao.php';
require_once 'modeles/visite.class.php';
require_once 'modeles/visite.dao.php';
require_once 'modeles/engagement.class.php';
require_once 'modeles/engagement.dao.php';
require_once 'modeles/post.class.php';
require_once 'modeles/post.dao.php';
require_once 'modeles/voyageur.class.php';
require_once 'modeles/voyageur.dao.php';

//Ajout des contrôleurs
require_once 'controllers/controller.class.php';
require_once 'controllers/controller.guide.class.php';
require_once 'controllers/controller.excursion.php';
require_once 'controllers/controller.voyageur.php';

?>

