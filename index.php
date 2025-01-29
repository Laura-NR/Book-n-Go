<?php
// pas sur que ce soit nécessaire mais au cas où : A VOIR
// set_include_path('.;C:/wamp64/www/Book-n-Go;C:/wamp64/backupscripts');
//echo get_include_path();
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['role'])) {
        $_SESSION['role'] = "visiteur";
}
require_once "include.php";

$twig->addGlobal('session', $_SESSION);

try {
    $method = $_SERVER['REQUEST_METHOD'];
    $params = array_merge($_GET, $_POST);

    if (isset($params['controleur'])) {
        $controllerName = htmlspecialchars($params['controleur'], ENT_QUOTES, 'UTF-8');
    } else {
        $controllerName = '';
    }
    if (isset($params['methode'])) {
        $methode = htmlspecialchars($params['methode'], ENT_QUOTES, 'UTF-8');
    } else {
        $methode = '';
    }

    if ($controllerName == '' && $methode == '') {
        $messages = $_SESSION['messages_alertes'] ?? [];

        echo $twig->render('pageAccueil_template.html.twig', [
            'app' => [
                'session' => $_SESSION,
                'messages_alertes' => $messages  
            ]
        ]);
        
        unset($_SESSION['messages_alertes']);
    }
    else{
        if ($controllerName == '') {
            throw new Exception('Le controleur n\'est pas défini');
        }

        if ($methode == '') {
            throw new Exception('La méthode n\'est pas définie');
        }

        $controller = ControllerFactory::getController($controllerName, $twig, $loader);
        $controller->call($methode);
    }


} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}


/* try {
    // Vérifier si le backup peut être lancé
    require_once '../../backupscripts/backup_trigger.php';
    // Déclenchement de backup
    AutoBackup::lancer_backup();
} catch (Exception $e) {
    error_log("Backup trigger failed: " . $e->getMessage());
} */


//$last_backup = filemtime('/tmp/backup.lock');
//// si pas de backup, ou alors temps d'intervalle écoulé -> on déclenche le backup
//if (!$last_backup || time() - $last_backup > 86400) { // 24 heures
//    require_once '../../backupscripts/backup_trigger.php';
//    AutoBackup::lancer_backup();
//}

?>