<?php
abstract class Voyageur {
//attributs   
private ?int $id;
private ?string $nom;
private ?string $prenom;   
private ?string $numeroTel;
private ?string $mail;   
private ?string $mdp;   

//constructeur et destruteurs 
public function __construct(?int $id = null, ?string $nom = null,?string $prenom = null, ?string $numeroTel = null,?string $mail = null, ?string $mdp = null,?string $cheminCertification = null) {
    $this->id = $id;
    $this-> nom =$nom ;
    $this->prenom = $prenom;   
    $this-> numeroTel = $numeroTel;
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
        return $this->numeroTel; 
    }
    public function setNumeroTel(string $numeroTel): void
    { 
        $this->numeroTel = $numeroTel; 
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