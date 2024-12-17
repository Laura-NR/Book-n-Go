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
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Composer");
        $composer = $pdoStatement->fetch();
        return $composer;
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
        $composer->setTempsSurPlace($tableauAssoc["temps_sur_place"]);
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
}
