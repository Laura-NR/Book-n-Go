<?php

class EngagementDao {
    private PDO $pdo;

    public function __construct(PDO $pdo=null)
    {
        $this->pdo = bd::getInstance()->getPdo();
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM engagement";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $engagements = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        return $engagements;
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

    public function findAssoc(?int $id): ?Engagement
    {
        $sql = "SELECT * FROM engagement WHERE id = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array(':id' => $id));
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $engagement = $pdoStatement->fetch();
        return $engagement;
    }

    public function find(?int $id): ?Engagement
    {
        $sql = "SELECT * FROM engagement WHERE id = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array(':id' => $id));
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Engagement');
        $engagement = $pdoStatement->fetch();
        return $engagement;
    } 

    public function hydrate(array $tableauAssoc): ?Engagement // DATES A CORRIGER
    {
        $engagement = new Engagement();
        $engagement->setId($tableauAssoc['id']);
        $engagement->setDateDebutDispo(new DateTime($tableauAssoc['date_debut_dispo']));
        $engagement->setDateFinDispo(new DateTime($tableauAssoc['date_fin_dispo']));
        $engagement->setExcursion($tableauAssoc['id_excursion']);
        $engagement->setGuide($tableauAssoc['id_guide']);
        $engagement->setHeureDebut(new DateTime($tableauAssoc['heure_debut']));
        return $engagement;
    }

    public function hydrateAll(array $tableauAssoc): ?array
    {
        $engagements = [];
        foreach ($tableauAssoc as $ligne) {
            $engagement = new Engagement();
            $engagement = $this->hydrate($ligne);
            $engagements[] = $engagement;
        }
        return $engagements;
    }

    public function creer(Engagement $engagement): bool
    {
        $sql = "INSERT INTO engagement (date_debut_dispo, date_fin_dispo, id_excursion, id_guide, heure_debut) VALUES (:date_debut_dispo, :date_fin_dispo, :id_excursion, :id_guide, :heure_debut)";
        $pdoStatement = $this->pdo->prepare($sql);
        return $pdoStatement->execute(array(
            ':date_debut_dispo' => $engagement->getDateDebutDispo()->format('Y-m-d'),
            ':date_fin_dispo' => $engagement->getDateFinDispo()->format('Y-m-d'),
            ':id_excursion' => $engagement->getExcursion(),
            ':id_guide' => $engagement->getIdGuide(),
            ':heure_debut' => $engagement->getHeureDebut()->format('Y-m-d H:i:s'),
        ));
    }
    public function findEngagementsByExcursionId(int $excursionId): array
    {
        $sql = "SELECT * FROM engagement WHERE id_excursion = :id_excursion";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id_excursion', $excursionId, PDO::PARAM_INT);
        $stmt->execute();

        $engagements = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $engagements[] = new Engagement(
                $row['id'],
                new DateTime($row['date_debut_dispo']),
                new DateTime($row['date_fin_dispo']),
                $row['id_excursion'],
                $row['id_guide'],
                new DateTime($row['heure_debut'])
            );
        }
        return $engagements;
    }
}