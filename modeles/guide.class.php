<?php 
require_once 'include.php';
class Guide extends Utilisateur {
    // Attribut spécifique à Guide
    private ?string $cheminCertification;

    // Constructeur
    public function __construct(?int $id = null, ?string $nom = null, ?string $prenom = null, ?string $numeroTel = null, ?string $mail = null, ?string $mdp = null, ?string $cheminCertification = null) {
        // Appel du constructeur parent (Utilisateur)
        parent::__construct($id, $nom, $prenom, $numeroTel, $mail, $mdp);
        $this->cheminCertification = $cheminCertification;
    }

    // Getter et Setter pour cheminCertification
    public function getCheminCertification(): ?string {
        return $this->cheminCertification;
    }

    public function setCheminCertification(?string $cheminCertification): self {
        $this->cheminCertification = $cheminCertification;
        return $this;
    }
}
?>
