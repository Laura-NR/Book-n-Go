<?php
class ComposerDao{
    private ?PDO $pdo;

    //Constructeur
    public function __construct(?PDO $pdo = null){
        $this->pdo = $pdo;
    }

    //Getteur
    public function getPdo(){
        return $this->pdo;
    }

    //Setteur
    public function setPdo(?PDO $pdo = null){
        $this->pdo = $pdo;
    }

    public function find(?int $id): ?Composer{
        $sql ="SELECT * FROM composer WHERE id= :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array("id"=>$id));
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,"Composer");
        $composer = $pdoStatement->fetch();
        return $composer;
    }

    public function findAll(){
        $sql ="SELECT * FROM composer";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,"Composer");
        $composer = $pdoStatement->fetchAll();
        return $composer;
    }

    public function findAssoc(?int $id): ?array{
        $sql ="SELECT * FROM composer WHERE id= :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array("id"=>$id));
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $composer = $pdoStatement->fetch();
        return $composer;
    }

    public function findAllAssoc(){
        $sql ="SELECT * FROM composer";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $composer = $pdoStatement->fetchAll();
        return $composer;
    }

    public function hydrate($tableauAssoc): ?Composer{
        $composer = new Composer();
        $composer->setId($tableauAssoc["id"]);
        $composer->setAddress($tableauAssoc["id"]);
        $composer->setDescription($tableauAssoc["id"]);
        $composer->setTitre($tableauAssoc["id"]);
        return $composer;
    }

    public function hydrateAll($tab): ?array{
        $ComposerTab = [];
        foreach ($tab as $tableauAssoc) {
            $composer = $this->hydrate($tableauAssoc);
            $ComposerTab[] = $composer;
        }
        return $ComposerTab;
    }
}