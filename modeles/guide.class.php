<?php
/**
 * @file guide.class.php
 * @class Guide
 * @brief Classe représentant un guide.
 *
 * La classe `Guide` hérite de la classe `Voyageur` et ajoute des fonctionnalités spécifiques aux guides,
 * comme la gestion du chemin de certification.
 *
 * Cette classe est utilisée pour représenter un guide dans l'application, avec ses informations personnelles
 * et son fichier de certification. Elle peut être étendue pour inclure d'autres fonctionnalités spécifiques aux guides.
 */
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
     * @param DateTime|null $derniere_co
     * @param int|null $tentativesEchouees
     * @param DateTime|null $dateDernierEchec
     * @param string|null $statutCompte
     * @param string|null $cheminCertification
     */
    public function __construct(?int $id = null, ?string $nom = null, ?string $prenom = null, ?string $numeroTel = null, ?string $mail = null, ?string $mdp = null, ?DateTime $derniere_co = null, ?int $tentativesEchouees = 0, ?DateTime $dateDernierEchec = null, ?string $statutCompte = 'actif', ?string $cheminCertification = null) {
        // Appel du constructeur parent (Voyageur)
        parent::__construct($id, $nom, $prenom, $numeroTel, $mail, $mdp, $derniere_co, $tentativesEchouees, $dateDernierEchec, $statutCompte);
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
