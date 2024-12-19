<?php
class Visite{
    private int|null $id;
    private string|null $adresse;
    private string|null $ville;
    private int|null $codePostal;
    private string|null $description;
    private string|null $titre;

    private int|null $idGuide;

    //Constructeur
    public function __construct(?int $id = null, ?string $adresse = null, ?string $ville = null, ?int $codePostal = null, ?string $description = null, ?string $titre = null, ?int $idGuide = null){
        $this->id = $id;
        $this->adresse = $adresse;
        $this->ville = $ville;
        $this->codePostal = $codePostal;
        $this->description = $description;
        $this->titre = $titre;
        $this->idGuide = $idGuide;
    }

    //Getteur
    public function getId(){
        return $this->id;
    }

    public function getAdresse(): ?string{
        return $this->adresse;
    }

    public function getVille(): ?string{
        return $this->ville;
    }

    public function getCodePostal(): ?int{
        return $this->codePostal;
    }

    public function getDescription(): ?string{
        return $this->description;
    }

    public function getTitre(): ?string{
        return $this->titre;
    }

    /**
     * @return int|null
     */public function getIdGuide(): ?int{
    return $this->idGuide;
    }
    //Setteur
    public function setId(?int $id): void{
        $this->id = $id;
    }

    public function setAdresse(?string $adresse): void{
        $this->adresse = $adresse;
    }

    public function setVille(?string $ville): void{
        $this->ville = $ville;
    }

    public function setCodePostal(?int $codePostal): void{
        $this->codePostal = $codePostal;
    }

    public function setDescription(?string $description): void{
        $this->description = $description;
    }

    public function setTitre(?string $titre): void{
        $this->titre = $titre;
    }

    /**
     * @param int|null $idGuide
     */
    public function setIdGuide(?int $idGuide): void{
        $this->idGuide = $idGuide;
    }
}
?>