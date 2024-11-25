<?php
require_once 'utilisateur.class.php';
class Voyageur extends Utilisateur {
//constructeur et destruteurs 
    public function __construct(?int $id = null, ?string $nom = null,?string $prenom = null, ?string $numeroTel = null,?string $mail = null, ?string $mdp = null,?string $cheminCertification = null) {
        parent::__construct($id,$nom,$prenom,$numeroTel,$mail,$mdp,$cheminCertification);
    }
}
?>