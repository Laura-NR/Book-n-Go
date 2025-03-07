<?php

//Ajout de l'autoload de composer
require_once 'vendor/autoload.php';

//Ajout du modèle de la classe config pour configurer l'application

//require_once 'config/constantes.php';
require_once 'modeles/config.class.php';

//Ajout du code pour initialiser twig
require_once 'config/twig.php';

//Ajout du modèle de connexion à la base de données
require_once 'modeles/bd.class.php';

//Ajout des modèles
require_once 'modeles/carnet_voyage.class.php';
require_once 'modeles/carnet_voyage.dao.php';
require_once 'modeles/commentaire.dao.php';
require_once 'modeles/commentaire.class.php';
require_once 'modeles/composer.class.php';
require_once 'modeles/composer.dao.php';
require_once 'modeles/excursion.class.php';
require_once 'modeles/excursion.dao.php';
require_once 'modeles/visite.class.php';
require_once 'modeles/visite.dao.php';
require_once 'modeles/voyageur.class.php';
require_once 'modeles/voyageur.dao.php';
require_once 'modeles/utilisateur.class.php';
require_once 'modeles/utilisateur.dao.php';
require_once 'modeles/guide.class.php';
require_once 'modeles/guide.dao.php';
require_once 'modeles/post.class.php';
require_once 'modeles/post.dao.php';
require_once 'modeles/reservation.class.php';
require_once 'modeles/reservation.dao.php';
require_once 'modeles/engagement.class.php';
require_once 'modeles/engagement.dao.php';

//Ajout des fichiers des règles de validation
require_once 'validation/ajout_commentaire.php';
require_once 'validation/ajout_excursion.php';
require_once 'validation/ajout_guide.php';
require_once 'validation/ajout_visite.php';
require_once 'validation/ajout_voyageur.php';
require_once 'validation/connexion.php';
require_once 'validation/validator.class.php';

//ajout des controllers
require_once 'controllers/controller.class.php';
require_once 'controllers/controller_factory.class.php';
require_once 'controllers/controller.carnet.class.php';
require_once 'controllers/controller.guide.php';
require_once 'controllers/controller.login.php';
require_once 'controllers/controller.utilisateur.php';
require_once 'controllers/controller.visite.php';
require_once 'controllers/controller.excursion.php';
require_once 'controllers/controller.voyageur.php';
require_once 'controllers/controller.post.class.php';
require_once 'controllers/controller.commentaire.php';
require_once 'controllers/controller.reservation.php';
require_once 'controllers/controller.engagement.php';

//Ajout des services
require_once 'services/BreadcrumbService.php';

//Ajout des routes
require_once 'config/breadcrumb_routes.php';