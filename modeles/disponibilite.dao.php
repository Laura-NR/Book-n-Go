<?php

class DisponibiliteDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo=null)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM disponibilite";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $disponibilites = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        return $disponibilites;
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

    public function findAssoc(?int $id): ?Disponibilite
    {
        $sql = "SELECT * FROM disponibilite WHERE id = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array(':id' => $id));
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $disponibilite = $pdoStatement->fetch();
        return $disponibilite;
    }

    public function find(?int $id): ?Disponibilite
    {
        $sql = "SELECT * FROM disponibilite WHERE id = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array(':id' => $id));
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Disponibilite');
        $disponibilite = $pdoStatement->fetch();
        return $disponibilite;
    } 

    public function hydrate(array $tableauAssoc): ?Disponibilite
    {
        $disponibilite = new Disponibilite();
        $disponibilite->setId($tableauAssoc['id']);
        $disponibilite->setDate_debut($tableauAssoc['date_debut_dispo']);
        $disponibilite->setDate_fin($tableauAssoc['date_fin_dispo']);
        $disponibilite->setVisite($tableauAssoc['id_visite']);
        $disponibilite->setGuide($tableauAssoc['id_guide']);
        return $disponibilite;
    }

    public function hydrateAll(array $tableauAssoc): ?array
    {
        $disponibilites = [];
        foreach ($tableauAssoc as $ligne) {
            $disponibilite = new Disponibilite();
            $disponibilite = $this->hydrate($ligne);
            $disponibilites[] = $disponibilite;
        }
        return $disponibilites;
    }
}