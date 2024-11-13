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

    public function find(?int $id_visite, ?int $id_point): ?Composer{
        $sql ="SELECT * FROM composer WHERE id_visite=:id_visite AND id_point=:id_point";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array("id_visite"=>$id_visite, "id_point"=>$id_point));
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
        $sql ="SELECT * FROM composer WHERE id_visite=:id_visite AND id_point=:id_point";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array("id_visite"=>$id_visite, "id_point"=>$id_point));
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
        $composer->setHeureArr($tableauAssoc["heure_arrivee"]);
        $composer->setTempsSurPlace($tableauAssoc["temps_sur_place"]);
        $composer->setVisite($tableauAssoc["id_visite"]);
        $composer->setPoint($tableauAssoc["id_point"]);
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