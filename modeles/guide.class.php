<?php 

class Guide extends Voyageur {
   // Attribut spécifique à Guide
    private ?string $chemin_certif;

    // Constructeur
    public function __construct(?int $id = null, ?string $nom = null, ?string $prenom = null, ?string $numeroTel = null, ?string $mail = null, ?string $mdp = null, ?string $cheminCertification = null) {
        // Appel du constructeur parent (Utilisateur)
        parent::__construct($id, $nom, $prenom, $numeroTel, $mail, $mdp);
        $this->chemin_certif = $cheminCertification;
    }

   // Getter et Setter pour cheminCertification
    public function getCheminCertification(): ?string {
        return $this->chemin_certif;
    }

    public function setCheminCertification(?string $cheminCertification): self {
        $this->chemin_certif = $cheminCertification;
        return $this;
    }
}
?>
