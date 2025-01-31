<?php
/**
 * @file composer.dao.php
 * @class ComposerDao
 * @brief Classe pour la gestion des requêtes en base de données
 * concernant les relations entre les utilisateurs et les carnets de voyage
 */
class ComposerDao
{
    private ?PDO $pdo;

    //Constructeur

    /**
     * @brief Constructeur
     * @param PDO|null $pdo
     */
    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = bd::getInstance()->getPdo();
    }

    //Getteur

    /**
     * @brief Retourne le PDO
     * @return PDO|null
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    //Setteur

    /**
     * @brief Affecte le PDO
     * @param PDO|null $pdo
     * @return void
     */
    public function setPdo(?PDO $pdo = null)
    {
        $this->pdo = $pdo;
    }

    /**
     * @brief Insere un Composer dans la base de données
     * @param Composer $composer
     * @return bool
     */
    public function creer(Composer $composer): bool
    {
        $sql = "INSERT INTO composer (temps_sur_place, id_excursion, id_visite)
                VALUES (:temps_sur_place, :ordre, :id_excursion, :id_visite)";
        $stmt = $this->pdo->prepare($sql);

        $tempsSurPlace = $composer->getTempsSurPlace() ? $composer->getTempsSurPlace()->format('Y-m-d H:i:s') : null;

        return $stmt->execute([
            ':temps_sur_place' => $tempsSurPlace,
            ':ordre' => $ordre,
            ':id_excursion' => $composer->getExcursion(),
            ':id_visite' => $composer->getVisite()
        ]);
    }

    /**
     * @brief Recherche un Composer dans la base de données possédant un id_excursion et un id_visite spécifique
     * @param int|null $id_excursion
     * @param int|null $id_visite
     * @return Composer|null
     */
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

    /**
     * @brief Recherche tous les Composer dans la base de données
     * @return array|false -> tableau associatif contenant des objets Composer récupérés
     */
    public function findAll()
    {
        $sql = "SELECT * FROM composer";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Composer");
        $composer = $pdoStatement->fetchAll();
        return $composer;
    }

    /**
     * @brief Recherche un Composer dans la base de données possédant un id_excursion et un id_visite spécifique
     * @param int|null $excursion
     * @param $visite
     * @return array|null -> tableau associatif contenant les informations du Composer récupéré
     */
    public function findAssoc(?int $excursion, $visite): ?array
    {
        $sql = "SELECT * FROM composer WHERE id_excursion=:id_excursion AND id_visite=:id_visite";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array("id_visite" => $excursion, "id_point" => $visite));
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $composer = $pdoStatement->fetch();
        return $composer;
    }

    /**
     * @brief Recherche tous les Composer dans la base de données
     * @return array|false -> tableau associatif contenant un tableau associatif par Composer récupéré(s)
     */
    public function findAllAssoc()
    {
        $sql = "SELECT * FROM composer";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $composer = $pdoStatement->fetchAll();
        return $composer;
    }

    /**
     * @brief Hydrate un tableau associatif en un Composer
     * @param $tableauAssoc
     * @return Composer|null
     * @throws DateMalformedStringException
     */
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

    /**
     * @brief Hydrate un tableau associatif en des objets Composer
     * @param $tab
     * @return array|null
     * @throws DateMalformedStringException
     */
    public function hydrateAll($tab): ?array
    {
        $ComposerTab = [];
        foreach ($tab as $tableauAssoc) {
            $composer = $this->hydrate($tableauAssoc);
            $ComposerTab[] = $composer;
        }
        return $ComposerTab;
    }

    /**
     * @brief Recherche toutes les Composer par le biais d'un id excursion
     * @param int $idExcursion -> id de l'excursion dont on cherche le Composer associé
     * @return array|null -> tableau associatif contenant les informations du Composer rencontré
     */
    public function findByExcursion(int $idExcursion): ?array
    {
        $sql = "
        SELECT v.id AS visite_id, v.titre, v.ville, c.temps_sur_place, c.ordre
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

    /**
     * @brief Modifie un Composer dans la base de données
     * @param Composer $composer
     * @return bool
     */
    public function modifier(Composer $composer): bool
    {
        $sql = "UPDATE composer 
            SET temps_sur_place = :temps_sur_place, ordre = :ordre
            WHERE id_excursion = :id_excursion AND id_visite = :id_visite";
        $stmt = $this->pdo->prepare($sql);

        $tempsSurPlace = $composer->getTempsSurPlace() ? $composer->getTempsSurPlace()->format('Y-m-d H:i:s') : null;

        return $stmt->execute([
            ':temps_sur_place' => $tempsSurPlace,
            ':ordre' => $ordre,
            ':id_excursion' => $composer->getExcursion(),
            ':id_visite' => $composer->getVisite()
        ]);
    }

    /**
     * @brief Supprime un Composer dans la base de données
     * @param int $id_excursion
     * @param int $id_visite
     * @return bool
     */
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
