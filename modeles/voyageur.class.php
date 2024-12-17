<?php
abstract class Voyageur {
//attributs   
private ?int $id;
private ?string $nom;
private ?string $prenom;   
private ?string $numero_tel;
private ?string $mail;   
private ?string $mdp;   


//constructeur et destruteurs 
public function __construct(?int $id = null, ?string $nom = null,?string $prenom = null, ?string $numero_tel = null,?string $mail = null, ?string $mdp = null,?string $cheminCertification = null) {
    $this->id = $id;
    $this-> nom =$nom ;
    $this->prenom = $prenom;   
    $this-> numero_tel = $numero_tel;
    $this->mail = $mail;   
    $this->mdp = $mdp;   
}

    // Getters et Setters
    public function getId(): int 
    { 
        return $this->id;
    }
    public function setId(int $id): void 
    { 
        $this->id = $id;
     }

    public function getNom(): string 
    {
         return $this->nom; 
        }
    public function setNom(string $nom): void 
    { 
        $this->nom = $nom; 
    }

    public function getPrenom(): string
    { 
        return $this->prenom; 
    }
    public function setPrenom(string $prenom): void 
    { 
        $this->prenom = $prenom; 
    }

    public function getNumeroTel(): string 
    { 
        return $this->numero_tel;
    }
    public function setNumeroTel(string $numeroTel): void
    { 
        $this->numero_tel = $numeroTel;
    }

    public function getMail(): string 
    { 
        return $this->mail;
    }
    public function setMail(string $mail): void 
    { 
        $this->mail = $mail; 
    }

    public function getMdp(): string
    { 
        return $this->mdp; 
    }
    public function setMdp(string $mdp): void 
    {
         $this->mdp = $mdp;
    }
}

?>