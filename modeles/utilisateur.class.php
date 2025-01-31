<?php
/**
 * @file utilisateur.class.php
 * @class Utilisateur
 * @brief Représentation d'un utilisateur.
 *
 * Cette classe permet de représenter un utilisateur, avec ses informations personnelles
 * et son rôle. Elle peut être étendue pour inclure d'autres fonctionnalités
 * spécifiques aux utilisateurs.
 */
class Utilisateur {
    /**
     * @var int|null
     */
    private ?int $id;
    /**
     * @var string|null
     */
    private ?string $nom;
    /**
     * @var string|null
     */
    private ?string $prenom;
    /**
     * @var string|null
     */
    private ?string $numeroTel;
    /**
     * @var string|null
     */
    private ?string $mail;
    /**
     * @var string|null
     */
    private ?string $mdp;
    /**
     * @var string|null
     */
    private ?string $role;
    private ?string $statut;

    /**
     * @param int|null $id
     * @param string|null $nom
     * @param string|null $prenom
     * @param string|null $numeroTel
     * @param string|null $mail
     * @param string|null $mdp
     * @param string|null $role
     * @param string|null $statut;
     */
    public function __construct(?int    $id = null, ?string $nom = null, ?string $prenom = null,
                                ?string $numeroTel = null, ?string $mail = null, ?string $mdp = null,
                                ?string $role = null, ?string $statut = null) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->numeroTel = $numeroTel;
        $this->mail = $mail;
        $this->mdp = $mdp;
        $this->role = $role;
        $this->statut = $statut;

    }

    /**
     * @return int|null
     */
    public function getId(): ?int {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getNom(): ?string {
        return $this->nom;
    }

    /**
     * @return string|null
     */
    public function getPrenom(): ?string {
        return $this->prenom;
    }

    /**
     * @return string|null
     */
    public function getNumeroTel(): ?string {
        return $this->numeroTel;
    }

    /**
     * @return string|null
     */
    public function getMail(): ?string {
        return $this->mail;
    }

    /**
     * @return string|null
     */
    public function getMdp(): ?string {
        return $this->mdp;
    }

    /**
     * @return string|null
     */
    public function getRole(): ?string {
        return $this->role;
    }

    /**
     * @param string $role
     * @return void
     */
    public function setRole(string $role): void {
        $this->role = $role;
    }

    /**
     * @var string|null
     */

// Ajout d'un getter pour le statut
    public function getStatut(): ?string {
        return $this->statut;
    }



}
?>
