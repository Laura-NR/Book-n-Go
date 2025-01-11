<?php

/**
 * @file visite.dao.php
 * @class VisiteDao
 * @brief Classe permettant de gérer les visites dans la base de données.
 *
 * La classe `VisiteDao` fournit des méthodes pour interagir avec les visites dans la base de données.
 * Elle gère les opérations suivantes :
 * - Insertion de nouvelles visites.
 * - Modification d'une visite existante.
 * - Suppression d'une visite (commentée dans le code).
 * - Recherche d'une visite par son identifiant, par l'identifiant du guide qui l'a créer ou par la ville (commenté dans le code).
 * - Récupération de toute les visites.
 * - Conversion de un ou plusieurs tableaux associatifs représentant une ou plusieurs visites en objets `Visite` .
 *
 * Attribut principal :
 * - $pdo : Instance de PDO pour effectuer les opérations SQL.
 */

class VisiteDao
{
    private ?PDO $pdo;

    //Constructeur
     /**
     * Constructeur de la classe.
     * Initialise la connexion à la base de données via une instance de PDO.
     *
     * @param ?PDO $pdo Instance de PDO optionnelle. Si null, utilise une instance par défaut.
     */
    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = bd::getInstance()->getPdo();
    }

    //Getteur

    /**
     * But : Permet de retourner l'instance de PDO
     * @return PDO l'attribut de l'instance
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    //Setteur

    /**
     * But : Permet de changer l'attribut pdo de l'instance avec la valeur passer en paramètre donc $pdo
     * @param PDO $pdo données à affecter ou null si il n'est pas préciser
     */
    public function setPdo(?PDO $pdo = null)
    {
        $this->pdo = $pdo;
    }
    
    /**
     * But : Insère une nouvelle visite dans la base de données.
     *
     * @param array $data Données de la visite à insérer qui est composer de l'adresse, la ville, le codePostal, la description, le titre et idGuide.
     * @return Visite Retourne l'objet Visite créer en cas de succès, false sinon.
     */
    public function insert(array $data): ?Visite
    {
        $sql = "INSERT INTO visite (adresse, ville, codePostal, description, titre, idGuide)
        VALUES (:adresse,:ville,:codePostal,:description,:titre,:idGuide)";
        $stmt = $this->pdo->prepare($sql);
        if($stmt->execute(array(
            "adresse" => $data["adresse"],
            "ville" => $data["ville"],
            "codePostal" => $data["codePostal"],
            "description" => $data["description"],
            "titre" => $data["titre"],
            "idGuide" => $data["idGuide"],
            )))
            {
                return $this->find($this->pdo->lastInsertId());
            }
            else {
                return false;
            }
    }

    // public function delete(int $id) : bool {
    //     $sql = "DELETE FROM visite WHERE visite.id = :id";
    //     $stmt = $this->pdo->prepare($sql);
    //     return $stmt->execute(array(
    //         "id" => $id,
    //     ));
    // }

    /**
     * But : Modifier une visite déjà existante dans la base de données.
     *
     * @param array $data Nouvelle données de la visite qui est composer de l'adresse, la ville, le codePostal, la description, le titre et idGuide, ainsi que l'id de la visite à modifier.
     * @return bool Retourne true en cas de succès, false sinon.
     */
    public function modify(array $data): bool
    {
        $sql = "UPDATE visite 
        SET adresse = :adresse, ville = :ville, codePostal = :codePostal, description = :description, titre = :titre 
        WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array(
            "id" => $data["id"],
            "adresse" => $data["adresse"],
            "ville" => $data["ville"],
            "codePostal" => $data["codePostal"],
            "description" => $data["description"],
            "titre" => $data["titre"],

        ));
    }

    /**
     * But : Rechercher une visite dans la base de données grâce à son identifiant et l'obtenir sous forme d'objet.
     *
     * @param int $id identifiant de la visite à rechercher.
     * @return Visite Retourne l'objet visite correspondant.
     */
    public function find(?int $id): ?Visite
    {
        $sql = "SELECT * FROM visite WHERE id= :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array("id" => $id));
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "visite");
        $visite = $pdoStatement->fetch();

        if ($visite === false) {
            return null;
        }
        return $visite;
    }

    /**
     * But : Récupérer tout les visite de la base de données et les obtenir sous forme d'objet.
     *.
     * @return Visite Retourne les objets visite correspondant.
     */
    public function findAll()
    {
        $sql = "SELECT * FROM visite";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "visite");
        $visite = $pdoStatement->fetchAll();
        return $visite;
    }

    /**
     * But : Rechercher une visite dans la base de données grâce à son identifiant et le retourner sous forme d'un tableaux associatif.
     *
     * @param int $id identifiant de la visite à rechercher.
     * @return array Retourne le tableau associatif comportant les informations de la visite correspondant.
     */
    public function findAssoc(?int $id): ?array
    {
        $sql = "SELECT * FROM visite WHERE id= :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array("id" => $id));
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $visite = $pdoStatement->fetch();
        return $visite;
    }

    /**
     * But : Récupérer tout les visite de la base de données et les obtenir sous forme d'un tableau associatif.
     *.
     * @return array Retourne le tableau associatif contenant les visite correspondant.
     */
    public function findAllAssoc(): ?array
    {
        $sql = "SELECT * FROM visite";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $visite = $pdoStatement->fetchAll();

        return $visite;
    }

    /**
     * But : Convertir un tableau associatif contenant les informations d'un visite en un objet visite.
     *
     * @param array $tableauAssoc Tableau associatif représentnant une visite.
     * @return Visite Retourne l'objet visite qui est constitué des information données en paramètre.
     */
    public function hydrate($tableauAssoc): ?Visite
    {
        $visite = new Visite();
        $visite->setId($tableauAssoc["id"]);
        $visite->setAdresse($tableauAssoc["adresse"]);
        $visite->setVille($tableauAssoc["ville"]);
        $visite->setCodePostal($tableauAssoc["codePostal"]);
        $visite->setDescription($tableauAssoc["description"]);
        $visite->setTitre($tableauAssoc["titre"]);
        $visite->setIdGuide($tableauAssoc["idGuide"]);
        return $visite;
    }

    /**
     * But : Convertir un tableau associatif contenant les informations de plusieurs visite en un tableau d'objets visite.
     *
     * @param array $tab Tableau associatif représentnant les visite.
     * @return array Retourne le tableau d'objet visite.
     */
    public function hydrateAll($tab): ?array
    {
        $visiteTab = [];
        foreach ($tab as $tableauAssoc) {
            $visite = $this->hydrate($tableauAssoc);
            print_r($visite);
            $visiteTab[] = $visite;
        }
        return $visiteTab;
    }

    /*public function findByVille(string $ville): array
    {
        $sql = "SELECT * FROM visite WHERE ville = :ville";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(["ville" => $ville]);
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "visite");
        $visite = $pdoStatement->fetchAll();
        return $visite;
    }*/

    /**
     * Recherche toutes les visites d'un guide spécifique.
     *
     * @param string $idGuide Identifiant du guide.
     * @return array Retourne un tableau d'objets Visite.
     */
    public function findByGuide(string $idGuide): array
    {
        $sql = "SELECT * FROM visite WHERE idGuide = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(["id" => $idGuide]);
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "visite");
        $visite = $pdoStatement->fetchAll();
        return $visite;
    }
}
