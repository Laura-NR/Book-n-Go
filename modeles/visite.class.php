<?php
/**
 * @file visite.class.php
 * @class Visite
 * @brief Classe représentant une visite.
 *
 * La classe `Visite` représente une visite avec plusieurs attributs essentielles :
 * - Identifiant ($id) : Un identifiant unique pour la visite.
 * - Adresse ($adresse), ville ($ville), et code postal ($codePostal) : Informations sur l'emplacement où se déroule la visite.
 * - Description ($description) : Une description détaillée de la visite.
 * - Identifiant du guide ($idGuide) : L'identifiant du guide qui a créé la visite.
 * 
 * La classe inclut des méthodes permettant de modifier et de récupérer les différents attributs mentionnés précédemment.
 */

class Visite
{
    //Attribut
    /**
     * @var int
     */
    private ?int $id;

    /**
     * @var string
     */
    private ?string $adresse;

    /**
     * @var string
     */
    private ?string $ville;

    /**
     * @var string
     */
    private ?string $codePostal;

    /**
     * @var string
     */
    private ?string $description;

    /**
     * @var string
     */
    private ?string $titre;

    /**
     * @var int
     */
    private ?int $idGuide;

    //Constructeur
    /**
     * Constructeur de la classe
     * But : Créer une instance de la classe Visite
     * 
     * @param int $id permet de préciser l'identifiant qu'on souhaite attribuer a notre instance il est optionnelle est sera égal a null si il n'est pas préciser.
     * @param string $adresse permet de préciser l'adresse qu'on souhaite attribuer a notre instance il est optionnelle est sera égal a null si il n'est pas préciser.
     * @param string $ville permet de préciser la ville t qu'on souhaite attribuer a notre instance il est optionnelle est sera égal a null si il n'est pas préciser.
     * @param string $codePostal permet de préciser le code postal qu'on souhaite attribuer a notre instance il est optionnelle est sera égal a null si il n'est pas préciser.
     * @param string $description permet de préciser la description qu'on souhaite attribuer a notre instance il est optionnelle est sera égal a null si il n'est pas préciser.
     * @param string $titre permet de préciser le titre qu'on souhaite attribuer a notre instance il est optionnelle est sera égal a null si il n'est pas préciser.
     * @param int $idGuide permet de préciser l'identifiant du guide qu'on souhaite attribuer a notre instance il est optionnelle est sera égal a null si il n'est pas préciser.
     */
    public function __construct(?int $id = null, ?string $adresse = null, ?string $ville = null, ?string $codePostal = null, ?string $description = null, ?string $titre = null, ?int $idGuide = null)
    {
        $this->id = $id;
        $this->adresse = $adresse;
        $this->ville = $ville;
        $this->codePostal = $codePostal;
        $this->description = $description;
        $this->titre = $titre;
        $this->idGuide = $idGuide;
    }

    //Getteur

    /** Getteur de la classe */

    /**
     * But : Permet de retourner l'identifiant de à la visite
     * @return int l'attribut de l'instance
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * But : Permet de retourner l'adresse de la visite
     * @return string l'attribut de l'instance
     */
    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    /**
     * But : Permet de retourner la ville de la visite
     * @return string l'attribut de l'instance
     */
    public function getVille(): ?string
    {
        return $this->ville;
    }

    /**
     * But : Permet de retourner le code postal de la visite
     * @return string l'attribut de l'instance
     */
    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    /**
     * But : Permet de retourner la description de la visite
     * @return string l'attribut de l'instance
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * But : Permet de retourner le titre de la visite
     * @return string l'attribut de l'instance
     */
    public function getTitre(): ?string
    {
        return $this->titre;
    }

    /**
     * But : Permet de retourner l'identifiant du Guide lié à la visite
     * @return int l'attribut de l'instance
     */
    public function getIdGuide(): ?int
    {
        return $this->idGuide;
    }

    //Setteur

    /** Setteur de la classe */

    /**
     * But : Permet de changer l'attribut id de l'instance avec la valeur passer en paramètre donc $id
     * @param int $id données à affecter
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * But : Permet de changer l'attribut adresse de l'instance avec la valeur passer en paramètre donc $adresse
     * @param string $adresse données à affecter
     */
    public function setAdresse(?string $adresse): void
    {
        $this->adresse = $adresse;
    }

    /**
     * But : Permet de changer l'attribut ville de l'instance avec la valeur passer en paramètre donc $ville
     * @param string $ville données à affecter
     */
    public function setVille(?string $ville): void
    {
        $this->ville = $ville;
    }

    /**
     * But : Permet de changer l'attribut codePostal de l'instance avec la valeur passer en paramètre donc $codePostal 
     * @param string $codePostal données à affecter
     */
    public function setCodePostal(?string $codePostal): void
    {
        $this->codePostal = $codePostal;
    }

    /**
     * But : Permet de changer l'attribut description de l'instance avec la valeur passer en paramètre donc $description
     * @param string $description données à affecter
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * But : Permet de changer l'attribut titre de l'instance avec la valeur passer en paramètre donc $titre
     * @param string $titre données à affecter
     */
    public function setTitre(?string $titre): void
    {
        $this->titre = $titre;
    }

    /**
     * But : Permet de changer l'attribut idGuide de l'instance avec la valeur passer en paramètre donc $idGuide
     * @param int $idGuide données à affecter
     */
    public function setIdGuide(?int $idGuide): void
    {
        $this->idGuide = $idGuide;
    }
}
