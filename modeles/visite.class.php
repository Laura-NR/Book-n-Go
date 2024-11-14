<?php
class Visite{
    private int|null $id;
    private string|null $address;
    private string|null $ville;
    private int|null $code_postal;
    private string|null $description;
    private string|null $titre;

    //Constructeur
    public function __construct(?int $id = null, ?string $address = null, ?string $ville, ?int $code_postal, ?string $description = null, ?string $titre = null){
        $this->id = $id;
        $this->address = $address;
        $this->ville = $ville;
        $this->code_postal = $code_postal;
        $this->description = $description;
        $this->titre = $titre;
    }

    //Getteur
    public function getId(){
        return $this->id;
    }

    public function getAddress(){
        return $this->address;
    }

    public function getVille(){
        return $this->ville;
    }

    public function getCodePostal(){
        return $this->code_postal;
    }

    public function getDescription(){
        return $this->description;
    }

    public function getTitre(){
        return $this->titre;
    }
    

    //Setteur
    public function setId(?int $id): void{
        $this->id = $id;
    }

    public function setAddress(?string $address): void{
        $this->address = $address;
    }

    public function setVille(?string $ville): void{
        $this->ville = $ville;
    }

    public function setCodePostal(?int $code_postal): void{
        $this->code_postal = $code_postal;
    }

    public function setDescription(?string $description): void{
        $this->description = $description;
    }

    public function setTitre(?string $titre): void{
        $this->titre = $titre;
    }
}
?>