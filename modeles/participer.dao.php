<?php

class ParticiperDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo=null)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM participer";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $posts = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        return $posts;
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

    /* public function findAssoc(?int $id_participation): ?Post
    {
        $sql = "SELECT * FROM participer WHERE id_participation = :id_participation";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array(':id_participation' => $id_participation));
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $participation = $pdoStatement->fetch();
        return $participation;
    }

    public function find(?int $id_participation): ?Post
    {
        $sql = "SELECT * FROM participer WHERE id_participation = :id_participation";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array(':id_participation' => $id_participation));
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Participer');
        $participation = $pdoStatement->fetch();
        return $participation;
    } */

    public function hydrate(array $tableauAssoc): ?Participer
    {
        $participation = new Participer();
        $participation->setDate_debut($tableauAssoc['date_debut_dispo']);
        $participation->setDate_fin($tableauAssoc['date_fin_dispo']);
        $participation->setVisite($tableauAssoc['id_visite']);
        $participation->setGuide($tableauAssoc['id_guide']);
        return $participation;
    }

    public function hydrateAll(array $tableauAssoc): ?array
    {
        $participations = [];
        foreach ($tableauAssoc as $ligne) {
            $participation = new Participer();
            $participation = $this->hydrate($ligne);
            $participations[] = $participation;
        }
        return $participations;
    }
}