<?php

/**
 * @file CarnetVoyage.class.php
 * @class CarnetVoyage
 * @brief Classe représentant un carnet de voyage
 */
class CarnetVoyage {
    /**
     * @var int|null
     * Id du carnet de voyage
     */
    private int|null $id;
    /**
     * @var string|null
     * titre du carnet
     */
    private string|null $titre;
    /**
     * @var string|null
     * chemin de l'image du carnet ( à préciser )
     */
    private string|null $chemin_img;
    /**
     * @var string|null
     * Description du carnet de voyage
     */
    private string|null $description;
    /**
     * @var int|null
     * Id do voyageur associé au carnet
     */
    private int|null $id_voyageur;

    /**
     * @param int|null $id
     * @param string|null $titre
     * @param string|null $chemin_img
     * @param string|null $description
     * @param Voyageur|null $voyageur
     * Constructeur de la classe CarnetVoyage
     */
    public function __construct(?int $id = null, ?string $titre = null, ?string $chemin_img = null, ?string $description = null, ?Voyageur $voyageur = null) {
        $this->id = $id;
        $this->titre = $titre;
        $this->chemin_img = $chemin_img;
        $this->description = $description;
        $this->voyageur = $voyageur;
    }

    /**
     * Récupère la valeur de id
     * @return int|null
     */ 
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set la valeur de l'Id
     * @param int|null $id
     * @return void
     */ 
    public function setId(?int $id):void
    {
        $this->id = $id;
    }

    /**
     * Récupère le titre du carnet
     * @return string|null
     */
    public function getTitre(): ?string
    {
        return $this->titre;
    }

    /**
     * Définit le titre du carnet
     * @param string|null $titre
     * @return void
     */ 
    public function setTitre(?string $titre):void
    {
        $this->titre = $titre;
    }

    /**
     * Récupère le chemin de l'image associé au carnet
     * @return string|null
     */
    public function getChemin_img(): ?string
    {
        return $this->chemin_img;
    }

    /**
     * Définit le chemin de l'image associé au carnet
     * @param string|null $chemin_img
     * @return void
     */ 
    public function setChemin_img(?string $chemin_img):void
    {
        $this->chemin_img = $chemin_img;
    }

    /**
     * Récupère la description du carnet
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Définit la description du carnet
     * @param string|null $description
     * @return void
     */ 
    public function setDescription(?string $description):void
    {
        $this->description = $description;
    }

//    /**
//     * Récupère le Voyageur associé au carnet
//     * @return Voyageur|null
//     */
//    public function getVoyageur(): ?Voyageur
//    {
//        return $this->voyageur;
//    }
//
//    /**
//     * Associe le Voyageur au carnet
//     * @uses Voyageur
//     */
//    public function setVoyageur(?Voyageur $voyageur):void
//    {
//        $this->voyageur = $voyageur;
//    }

    /**
     * Recupère l'id du voyageur
     * @return int|null
     */
    public function getIdVoyageur(): ?int
    {
        return $this->id_voyageur;
    }

    /**
     * Associe l'id du voyageur au carnet
     * @param int|null $id_voyageur
     * @return void
     */
    public function setIdVoyageur(?int $id_voyageur): void
    {
        $this->id_voyageur = $id_voyageur;
    }
    
}