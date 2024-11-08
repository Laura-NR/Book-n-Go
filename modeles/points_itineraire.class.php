<?php
class PointItineraire{
    private int|null $id;
    private string|null $address;
    private string|null $description;
    private string|null $titre;

    //Constructeur
    public function __construct(?int $id = null, ?string $address = null, ?string $description = null, ?string $tritre = null){
        $this->id = $id;
        $this->address = $address;
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

    public function setDescription(?string $description): void{
        $this->description = $description;
    }

    public function setTitre(?string $titre): void{
        $this->titre = $titre;
    }
}
?>