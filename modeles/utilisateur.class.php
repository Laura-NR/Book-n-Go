<?php
class Utilisateur {
    private ?int $id;
    private ?string $nom;
    private ?string $prenom;
    private ?string $numeroTel;
    private ?string $mail;
    private ?string $mdp;
    private ?string $role;  // Attribut rôle basé sur la présence de certification

    // Constructeur
    public function __construct(?int $id = null, ?string $nom = null, ?string $prenom = null, 
                                ?string $numeroTel = null, ?string $mail = null, ?string $mdp = null, 
                                ?string $role = null) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->numeroTel = $numeroTel;
        $this->mail = $mail;
        $this->mdp = $mdp;
        $this->role = $role;
    }

    // Getters et Setters
    public function getId(): ?int {
        return $this->id;
    }

    public function getNom(): ?string {
        return $this->nom;
    }

    public function getPrenom(): ?string {
        return $this->prenom;
    }

    public function getNumeroTel(): ?string {
        return $this->numeroTel;
    }

    public function getMail(): ?string {
        return $this->mail;
    }

    public function getMdp(): ?string {
        return $this->mdp;
    }

    public function getRole(): ?string {
        return $this->role;
    }

    public function setRole(string $role): void {
        $this->role = $role;
    }
}
?>
