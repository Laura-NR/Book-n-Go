<?php
class VisiteDao{
    private ?PDO $pdo;

    //Constructeur
    public function __construct(?PDO $pdo = null){
        $this->pdo = bd::getInstance()->getPdo();
    }

    //Getteur
    public function getPdo(){
        return $this->pdo;
    }

    //Setteur
    public function setPdo(?PDO $pdo = null){
        $this->pdo = $pdo;
    }

    public function insert(array $data) : bool {
        $sql = "INSERT INTO visite (adresse, ville, codePostal, description, titre, idGuide)
        VALUES (:adresse,:ville,:codePostal,:description,:titre,:idGuide)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array(
            "adresse" => $data["Adresse"],
            "ville" => $data["Ville"],
            "codePostal" => $data["Code Postal"],
            "description" => $data["Description"],
            "titre" => $data["Titre"],
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
    public function findAll(){
        $sql ="SELECT * FROM visite";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,"visite");
        $visite = $pdoStatement->fetchAll();
        return $visite;
    }

    public function findAssoc(?int $id): ?array{
        $sql ="SELECT * FROM visite WHERE id= :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array("id"=>$id));
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $visite = $pdoStatement->fetch();
        return $visite;
    }

    public function findAllAssoc(){
        $sql ="SELECT * FROM visite";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $visite = $pdoStatement->fetchAll();

        return $visite;
    }

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