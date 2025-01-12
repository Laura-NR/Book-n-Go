<?php
class Voyageur {
    // Constantes pour les statuts de compte
    private const MAX_TENTATIVES = 3;
    private const DELAI_REACTIVATION = '+5 minutes';
    private const STATUT_ACTIF = 'actif';
    private const STATUT_DESACTIVE = 'désactivé';

    private ?int $id;
    private ?string $nom;
    private ?string $prenom;
    private ?string $numero_tel;
    private ?string $mail;
    private ?string $mdp;
    private ?DateTime $derniere_co;

    // Nouveaux attributs
    private ?int $tentatives_echouees;
    private ?DateTime $date_dernier_echec;
    private ?string $statut_compte;

    // Constructeur mis à jour
    public function __construct(
        ?int $id = null,
        ?string $nom = null,
        ?string $prenom = null,
        ?string $numero_tel = null,
        ?string $mail = null,
        ?string $mdp = null,
        ?DateTime $derniere_co = null,
        ?int $tentatives_echouees = 0,
        ?DateTime $date_dernier_echec = null,
        ?string $statut_compte = self::STATUT_ACTIF
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->numero_tel = $numero_tel;
        $this->mail = $mail;
        $this->mdp = $mdp;
        $this->derniere_co = $derniere_co;
        $this->tentatives_echouees = $tentatives_echouees;
        $this->date_dernier_echec = $date_dernier_echec;
        $this->statut_compte = $statut_compte;
    }

    public function delaiAttenteEstEcoule(): bool {
        if (!$this->date_dernier_echec) {
            return true;
        }

        $delaiReactivation = clone $this->date_dernier_echec;
        $delaiReactivation->modify(self::DELAI_REACTIVATION);

        $maintenant = new DateTime();
        return $maintenant >= $delaiReactivation;
    }

    public function getTentativesEchouees(): ?int {
        return $this->tentatives_echouees;
    }

    public function setTentativesEchouees(?int $tentatives_echouees): void {
        $this->tentatives_echouees = $tentatives_echouees;
    }

    public function getDateDernierEchec(): ?DateTime {
        return $this->date_dernier_echec;
    }

    public function setDateDernierEchec(?DateTime $date_dernier_echec): void {
        $this->date_dernier_echec = $date_dernier_echec;
    }

    public function getStatutCompte(): ?string {
        return $this->statut_compte;
    }

    public function setStatutCompte(?string $statut_compte): void {
        $this->statut_compte = $statut_compte;
    }

    // Gère un échec de connexion
    public function gererEchecConnexion(): void {
        $this->tentatives_echouees++;
        $this->date_dernier_echec = new DateTime();

        if ($this->tentatives_echouees >= self::MAX_TENTATIVES) {
            $this->statut_compte = self::STATUT_DESACTIVE;
        }
    }

    // Réinitialise les tentatives de connexion
    public function reinitialiserTentativesConnexions(): void {
        $this->tentatives_echouees = 0;
        $this->date_dernier_echec = null;
    }

    // Renvoie le temps restant avant la réactivation du compte
    public function tempsRestantAvantReactivationCompte(): ?int {
        if (!$this->date_dernier_echec) {
            return null;
        }

        $delai = (new DateTime())->getTimestamp() - $this->date_dernier_echec->getTimestamp();
        return ($delai >= 15 * 60) ? null : 15 * 60 - $delai; // Temps restant en secondes
    }

    // Réactive le compte si le délai d'attente est écoulé
    public function reactiverCompteSiPossible(): bool {
        if ($this->delaiAttenteEstEcoule() && $this->statut_compte === self::STATUT_DESACTIVE) {
            $this->tentatives_echouees = 0;
            $this->statut_compte = self::STATUT_ACTIF;
            return true;
        }
        return false;
    }

    // Getters et Setters pour les attributs existants
    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getNom(): string {
        return $this->nom;
    }

    public function setNom(string $nom): void {
        $this->nom = $nom;
    }

    public function getPrenom(): string {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): void {
        $this->prenom = $prenom;
    }

    public function getNumeroTel(): string {
        return $this->numero_tel;
    }

    public function setNumeroTel(string $numeroTel): void {
        $this->numero_tel = $numeroTel;
    }

    public function getMail(): string {
        return $this->mail;
    }

    public function setMail(string $mail): void {
        $this->mail = $mail;
    }

    public function getMdp(): string {
        return $this->mdp;
    }

    public function setMdp(string $mdp): void {
        $this->mdp = $mdp;
    }

    public function getDerniereCo(): ?DateTime {
        return $this->derniere_co;
    }

    public function setDerniereCo(?DateTime $derniere_co): void {
        $this->derniere_co = $derniere_co;
    }
}
?>
