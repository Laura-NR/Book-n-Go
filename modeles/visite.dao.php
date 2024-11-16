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

    public function find(?int $id): ?Visite{
        $sql ="SELECT * FROM visite WHERE id= :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array("id"=>$id));
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,"visite");
        $visite = $pdoStatement->fetch();
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
        $visite->setAddress($tableauAssoc["adresse"]);
        $visite->setVille($tableauAssoc["ville"]);
        $visite->setCodePostal($tableauAssoc["code_postal"]);
        $visite->setDescription($tableauAssoc["description"]);
        $visite->setTitre($tableauAssoc["titre"]);
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

    public function findByVille(string $ville): array
    {
        $sql = "SELECT * FROM visite WHERE ville = :ville";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(["ville" => $ville]);
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "visite");
        $visite = $pdoStatement->fetchAll();
        return $visite;
    }
}
?>