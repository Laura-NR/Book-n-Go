<?php

class CarnetVoyageDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo=null)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM carnet_voyage";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $carnets = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        return $carnets;
    }

    /**
     * Get the value of pdo
     */ 
    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }

    /**
     * Set the value of pdo
     */ 
    public function setPdo(?PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    public function find(?int $id): ?CarnetVoyage
    {
        $sql = "SELECT * FROM carnet_voyage WHERE id = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array(':id' => $id));
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $carnet = $pdoStatement->fetch();
        return $carnet;
    }

    public function find1(?int $id): ?CarnetVoyage
    {
        $sql = "SELECT * FROM carnet_voyage WHERE id = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array(':id' => $id));
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Categorie');
        $carnet = $pdoStatement->fetch();
        return $carnet;
    }

    public function hydrate(array $tableauAssoc): ?CarnetVoyage
    {
        $carnet = new CarnetVoyage();
        $carnet->setId($tableauAssoc['id']);
        $carnet->setTitre($tableauAssoc['titre']);
        $carnet->setChemin_img($tableauAssoc['chemin_img']);
        $carnet->setDescription($tableauAssoc['description']);
        $carnet->setVoyageur($tableauAssoc['voyageur']);
        return $carnet;
    }

    public function hydrateAll(array $tableauAssoc): ?array
    {
        $carnets = [];
        foreach ($tableauAssoc as $ligne) {
            $carnet = new CarnetVoyage();
            $carnet = $this->hydrate($ligne);
            $carnets[] = $carnet;
        }
        return $carnets;
    }
}