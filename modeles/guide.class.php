<?php

class Guide extends Voyageur {
    // Attribut spécifique à Guide
    /**
     * @var string|null
     */
    private ?string $chemin_certif;

    // Constructeur
    /**
     * @param int|null $id
     * @param string|null $nom
     * @param string|null $prenom
     * @param string|null $numeroTel
     * @param string|null $mail
     * @param string|null $mdp
     * @param string|null $cheminCertification
     * @param int|null $tentativesEchouees
     * @param DateTime|null $dateDernierEchec
     * @param string|null $statutCompte
     */
    public function __construct(?int $id = null, ?string $nom = null, ?string $prenom = null, ?string $numeroTel = null, ?string $mail = null, ?string $mdp = null, ?string $cheminCertification = null, ?int $tentativesEchouees = 0, ?DateTime $dateDernierEchec = null, ?string $statutCompte = 'actif') {
        // Appel du constructeur parent (Voyageur)
        parent::__construct($id, $nom, $prenom, $numeroTel, $mail, $mdp, $tentativesEchouees, $dateDernierEchec, $statutCompte);
        $this->chemin_certif = $cheminCertification;
    }

    // Getter et Setter pour cheminCertification

    /**
     * @brief Retourne le chemin de la certification
     * @return string|null
     */
    public function getCheminCertification(): ?string {
        return $this->chemin_certif;
    }

    /**
     * @brief Affecte le chemin de la certification
     * @param string|null $cheminCertification
     * @return $this
     */
    public function setCheminCertification(?string $cheminCertification): self {
        $this->chemin_certif = $cheminCertification;
        return $this;
    }


}
?>
