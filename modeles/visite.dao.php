<?php
class VisiteDao{
    private ?PDO $pdo;

    /**
     * @param PDO|null $pdo
     */
    public function __construct(?PDO $pdo = null){
        $this->pdo = bd::getInstance()->getPdo();
    }

    /**
     * @return PDO|null
     */
    public function getPdo(){
        return $this->pdo;
    }

    /**
     * @param PDO|null $pdo
     * @return void
     */
    public function setPdo(?PDO $pdo = null){
        $this->pdo = $pdo;
    }

    /**
     * @brief Insérer une visite avec les données passées en paramètre
     * @param array $data
     * @return bool
     */
    public function insert(array $data) : bool {
        $sql = "INSERT INTO visite (adresse, ville, codePostal, description, titre, idGuide)
        VALUES (:adresse,:ville,:codePostal,:description,:titre,:idGuide)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array(
            "adresse" => $data["adresse"],
            "ville" => $data["ville"],
            "codePostal" => $data["codePostal"],
            "description" => $data["description"],
            "titre" => $data["titre"],
            "idGuide" => $data["idGuide"],
        ));
    }

    // public function delete(int $id) : bool {
    //     $sql = "DELETE FROM visite WHERE visite.id = :id";
    //     $stmt = $this->pdo->prepare($sql);
    //     return $stmt->execute(array(
    //         "id" => $id,
    //     ));
    // }


    /**
     * @brief Modifier une visite avec les données passées en paramètre
     * @param array $data
     * @return bool
     */
    public function modify(array $data) : bool {
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
     * @brief Trouver une visite par ID (objet Visite)
     * @param int|null $id
     * @return Visite|null
     */
    public function find(?int $id): ?Visite{
        $sql ="SELECT * FROM visite WHERE id= :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array("id"=>$id));
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,"visite");
        $visite = $pdoStatement->fetch();

        if ($visite === false) {
            return null;
        }
        return $visite;
    }

    /**
     * @brief Trouver toutes les visites (retourne un tableau associatif de toutes les visites (objets Visite))
     * @return array|false
     */
    public function findAll(){
        $sql ="SELECT * FROM visite";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,"visite");
        $visite = $pdoStatement->fetchAll();
        return $visite;
    }

    /**
     * @brief Trouver une visite par ID (tableau associatif)
     * @param int|null $id
     * @return array|null
     */
    public function findAssoc(?int $id): ?array{
        $sql ="SELECT * FROM visite WHERE id= :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array("id"=>$id));
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $visite = $pdoStatement->fetch();
        return $visite;
    }

    /**
     * @brief Trouver toutes les visites (retourne un tableau associatif de toutes les visites)
     * @return array|false
     */
    public function findAllAssoc(){
        $sql ="SELECT * FROM visite";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $visite = $pdoStatement->fetchAll();

        return $visite;
    }

    /**
     * @brief Hydrater une visite à partir d'un tableau associatif
     * @param $tableauAssoc
     * @return Visite|null
     */
    public function hydrate($tableauAssoc): ?Visite{
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
     * @brief Hydrater un tableau de visites à partir d'un tableau associatif
     * @param $tab
     * @return array|null
     */
    public function hydrateAll($tab): ?array{
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
     * @brief Trouver toutes les visites d'un guide dont l'id est spécifié en paramètre (retourne un tableau associatif de tous les objets visites)
     * @param string $idGuide
     * @return array
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
?>