<?php
class PointItineraireDao{
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

    public function find(?int $id): ?PointItineraire{
        $sql ="SELECT * FROM point_itineraire WHERE id= :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array("id"=>$id));
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,"point_itineraire");
        $pointItineraire = $pdoStatement->fetch();
        return $pointItineraire;
    }

    public function findAll(){
        $sql ="SELECT * FROM point_itineraire";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,"point_itineraire");
        $pointItineraire = $pdoStatement->fetchAll();
        return $pointItineraire;
    }

    public function findAssoc(?int $id): ?array{
        $sql ="SELECT * FROM point_itineraire WHERE id= :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array("id"=>$id));
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $pointItineraire = $pdoStatement->fetch();
        return $pointItineraire;
    }

    public function findAllAssoc(){
        $sql ="SELECT * FROM point_itineraire";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $pointItineraire = $pdoStatement->fetchAll();
        return $pointItineraire;
    }

    public function hydrate($tableauAssoc): ?PointItineraire{
        $pointItineraire = new PointItineraire();
        $pointItineraire->setId($tableauAssoc["id"]);
        $pointItineraire->setAddress($tableauAssoc["address"]);
        $pointItineraire->setDescription($tableauAssoc["description"]);
        $pointItineraire->setTitre($tableauAssoc["titre"]);
        return $pointItineraire;
    }

    public function hydrateAll($tab): ?array{
        $pointItineraireTab = [];
        foreach ($tab as $tableauAssoc) {
            $pointItineraire = $this->hydrate($tableauAssoc);
            $pointItineraireTab[] = $pointItineraire;
        }
        return $pointItineraireTab;
    }
}
?>