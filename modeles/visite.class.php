<?php
class Visite{
    /**
     * @var int|null
     */
    private ?int $id;
    /**
     * @var string|null
     */
    private ?string $adresse;
    /**
     * @var string|null
     */
    private ?string $ville;
    /**
     * @var int|null
     */
    private ?int $codePostal;
    /**
     * @var string|null
     */
    private ?string $description;
    /**
     * @var string|null
     */
    private ?string $titre;
    /**
     * @var int|null
     */
    private ?int $idGuide;

    //Constructeur

    /**
     * @param int|null $id
     * @param string|null $adresse
     * @param string|null $ville
     * @param int|null $codePostal
     * @param string|null $description
     * @param string|null $titre
     * @param int|null $idGuide
     */
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

    /**
     * @return int|null
     */
    public function getId(){
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getAdresse(): ?string{
        return $this->adresse;
    }

    /**
     * @return string|null
     */
    public function getVille(): ?string{
        return $this->ville;
    }

    /**
     * @return int|null
     */
    public function getCodePostal(): ?int{
        return $this->codePostal;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string{
        return $this->description;
    }

    /**
     * @return string|null
     */
    public function getTitre(): ?string{
        return $this->titre;
    }

    /**
     * @return int|null
     */public function getIdGuide(): ?int{
    return $this->idGuide;
    }
    //Setteur

    /**
     * @param int|null $id
     * @return void
     */
    public function setId(?int $id): void{
        $this->id = $id;
    }

    /**
     * @param string|null $adresse
     * @return void
     */
    public function setAdresse(?string $adresse): void{
        $this->adresse = $adresse;
    }

    /**
     * @param string|null $ville
     * @return void
     */
    public function setVille(?string $ville): void{
        $this->ville = $ville;
    }

    /**
     * @param int|null $codePostal
     * @return void
     */
    public function setCodePostal(?int $codePostal): void{
        $this->codePostal = $codePostal;
    }

    /**
     * @param string|null $description
     * @return void
     */
    public function setDescription(?string $description): void{
        $this->description = $description;
    }

    /**
     * @param string|null $titre
     * @return void
     */
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