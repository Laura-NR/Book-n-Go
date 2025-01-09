<?php
class ComposerDao
{
    private ?PDO $pdo;

    //Constructeur
    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = bd::getInstance()->getPdo();
    }

    //Getteur
    public function getPdo()
    {
        return $this->pdo;
    }

    //Setteur
    public function setPdo(?PDO $pdo = null)
    {
        $this->pdo = $pdo;
    }

    public function creer(Composer $composer): bool
    {
        $sql = "INSERT INTO composer (temps_sur_place, id_excursion, id_visite)
                VALUES (:temps_sur_place, :id_excursion, :id_visite)";
        $stmt = $this->pdo->prepare($sql);

        $tempsSurPlace = $composer->getTempsSurPlace() ? $composer->getTempsSurPlace()->format('Y-m-d H:i:s') : null;

        return $stmt->execute([
            ':temps_sur_place' => $tempsSurPlace,
            ':id_excursion' => $composer->getExcursion(),
            ':id_visite' => $composer->getVisite()
        ]);
    }

    public function find(?int $id_excursion, ?int $id_visite): ?Composer
    {
        $sql = "SELECT * FROM composer WHERE id_excursion=:id_excursion AND id_visite=:id_visite";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array("id_excursion" => $id_excursion, "id_visite" => $id_visite));
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $composer = $pdoStatement->fetch();
        
        if ($composer) {
            return $this->hydrate($composer);
        }

        return null;
    }

    public function findAll()
    {
        $sql = "SELECT * FROM composer";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Composer");
        $composer = $pdoStatement->fetchAll();
        return $composer;
    }

    public function findAssoc(?int $excursion, $visite): ?array
    {
        $sql = "SELECT * FROM composer WHERE id_excursion=:id_excursion AND id_visite=:id_visite";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array("id_visite" => $excursion, "id_point" => $visite));
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $composer = $pdoStatement->fetch();
        return $composer;
    }

    public function findAllAssoc()
    {
        $sql = "SELECT * FROM composer";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $composer = $pdoStatement->fetchAll();
        return $composer;
    }

    public function hydrate($tableauAssoc): ?Composer
    {
        $composer = new Composer();

        $tempsSurPlace = isset($tableauAssoc["temps_sur_place"]) && $tableauAssoc["temps_sur_place"] !== null
        ? new DateTime($tableauAssoc["temps_sur_place"])
        : null;

        $composer->setTempsSurPlace($tempsSurPlace);
        $composer->setExcursion($tableauAssoc["id_excursion"]);
        $composer->setVisite($tableauAssoc["id_visite"]);
        return $composer;
    }

    public function hydrateAll($tab): ?array
    {
        $ComposerTab = [];
        foreach ($tab as $tableauAssoc) {
            $composer = $this->hydrate($tableauAssoc);
            $ComposerTab[] = $composer;
        }
        return $ComposerTab;
    }

    public function findByExcursion(int $idExcursion): ?array
    {
        $sql = "
        SELECT v.id AS visite_id, v.titre, v.ville, c.temps_sur_place
        FROM visite v
        INNER JOIN composer c ON v.id = c.id_visite
        WHERE c.id_excursion = :id_excursion
        ";

        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(["id_excursion" => $idExcursion]);
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);

        $visites = $pdoStatement->fetchAll();
        return $visites;
    }

    public function modifier(Composer $composer): bool
    {
        $sql = "UPDATE composer 
            SET temps_sur_place = :temps_sur_place 
            WHERE id_excursion = :id_excursion AND id_visite = :id_visite";
        $stmt = $this->pdo->prepare($sql);

        $tempsSurPlace = $composer->getTempsSurPlace() ? $composer->getTempsSurPlace()->format('Y-m-d H:i:s') : null;

        return $stmt->execute([
            ':temps_sur_place' => $tempsSurPlace,
            ':id_excursion' => $composer->getExcursion(),
            ':id_visite' => $composer->getVisite()
        ]);
    }

    public function supprimer(int $id_excursion, int $id_visite): bool
    {
        $sql = "DELETE FROM composer WHERE id_excursion = :id_excursion AND id_visite = :id_visite";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id_excursion' => $id_excursion,
            ':id_visite' => $id_visite
        ]);
    }
}
