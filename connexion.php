<?php

require_once 'include.php';

$template = $twig->load('connexion_template.html.twig');
echo $template -> render();

?>