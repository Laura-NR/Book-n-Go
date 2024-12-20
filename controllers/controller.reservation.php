<?php
require_once 'controller.class.php';
require_once 'include.php';

class ControllerReservation extends BaseController {
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader) {
        parent::__construct($twig, $loader);
    }

}
?>