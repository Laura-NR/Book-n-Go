<?php

class Voyageur {
//attributs   
private ?int $id;
private ?string $nom;
private ?string $prenom;   
private ?string $numero_tel;
private ?string $mail;   
private ?string $mdp;
private ?DateTime $derniere_co;


    public function __construct(?int $id = null, ?string $nom = null,?string $prenom = null, ?string $numero_tel = null,?string $mail = null, ?string $mdp = null,?string $cheminCertification = null, ?DateTime $derniere_co = null) {
    $this->id = $id;
    $this-> nom =$nom ;
    $this->prenom = $prenom;   
    $this-> numero_tel = $numero_tel;
    $this->mail = $mail;   
    $this->mdp = $mdp;
    $this->derniere_co = $derniere_co;
}


    /**
     * @return int
     */
    public function getId(): int
    { 
        return $this->id;
    }

    /**
     * @param int $id
     * @return void
     */
    public function setId(int $id): void
    { 
        $this->id = $id;
     }

    /**
     * @return string
     */
    public function getNom(): string
    {
         return $this->nom; 
        }

    /**
     * @param string $nom
     * @return void
     */
    public function setNom(string $nom): void
    { 
        $this->nom = $nom; 
    }

    /**
     * @return string
     */
    public function getPrenom(): string
    { 
        return $this->prenom; 
    }

    /**
     * @param string $prenom
     * @return void
     */
    public function setPrenom(string $prenom): void
    { 
        $this->prenom = $prenom; 
    }

    /**
     * @return string
     */
    public function getNumeroTel(): string
    { 
        return $this->numero_tel;
    }

    /**
     * @param string $numeroTel
     * @return void
     */
    public function setNumeroTel(string $numeroTel): void
    { 
        $this->numero_tel = $numeroTel;
    }

    /**
     * @return string
     */
    public function getMail(): string
    { 
        return $this->mail;
    }

    /**
     * @param string $mail
     * @return void
     */
    public function setMail(string $mail): void
    { 
        $this->mail = $mail; 
    }

    /**
     * @return string
     */
    public function getMdp(): string
    { 
        return $this->mdp; 
    }

    /**
     * @param string $mdp
     * @return void
     */
    public function setMdp(string $mdp): void
    {
         $this->mdp = $mdp;
    }

    /**
     * @return DateTime|null
     */
    public function getDerniereCo(): ?DateTime
    {
        return $this->derniere_co;
    }

    /**
     * @param DateTime|null $derniere_co
     * @return void
     */
    public function setDerniereCo(?DateTime $derniere_co): void
    {
        $this->derniere_co = $derniere_co;
    }
}
?>
