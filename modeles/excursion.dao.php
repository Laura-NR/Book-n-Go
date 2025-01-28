<?php

/**
 * @file ExcursionDao.php
 * @brief Classe DAO pour la gestion des excursions dans la base de données.
 *
 * Cette classe gère les opérations CRUD pour les excursions. Elle permet de créer, lire, mettre à jour et supprimer des excursions. 
 * Elle fournit également des méthodes pour récupérer une excursion spécifique ou toutes les excursions.
 *
 * @class ExcursionDao
 * @brief Classe d'accès aux données (DAO) pour les excursions.
 */
class ExcursionDao
{
    private ?PDO $pdo; ///< Instance PDO pour interagir avec la base de données.

    /**
     * Constructeur de la classe ExcursionDao.
     * 
     * @param PDO|null $pdo Objet PDO pour la connexion à la base de données.
     */
    public function __construct(PDO $pdo = null)
    {
        $this->pdo = bd::getInstance()->getPdo();
    }

    /**
     * Crée une nouvelle excursion dans la base de données.
     * 
     * Cette méthode insère les informations d'une excursion dans la table `excursion`.
     * Elle retourne un objet `Excursion` avec les données de la nouvelle excursion, ou null en cas d'échec.
     *
     * @param array $data Données de l'excursion à insérer.
     * @return Excursion|null L'objet Excursion créé ou null si l'insertion échoue.
     * @throws PDOException Si une erreur de base de données se produit.
     */
    public function creer(array $data): ?Excursion
    {
        if ($data['date_creation'] instanceof DateTime) {
            $data['date_creation'] = $data['date_creation']->format('Y-m-d H:i:s');
        }

        $data['public'] = isset($data['public']) && $data['public'] === 'on' ? 1 : 0;

        try {
            $sql = "INSERT INTO excursion (capacite, nom, chemin_image, date_creation, description, public, id_guide)
                VALUES (:capacite, :nom, :chemin_image, :date_creation, :description, :public, :id_guide)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':capacite' => $data['capacite'],
                ':nom' => $data['nom'],
                ':chemin_image' => $data['chemin_image'],
                ':date_creation' => $data['date_creation'],
                ':description' => $data['description'],
                ':public' => $data['public'],
                ':id_guide' => $data['id_guide']
            ]);

            // Retourne l'objet Excursion correspondant à la nouvelle excursion insérée
            return $this->findAssoc($this->pdo->lastInsertId());
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            throw $e; // Relance l'exception
        }
    }

    /**
     * Sauvegarde une excursion existante (mise à jour dans la base de données).
     * 
     * @param Excursion $excursion L'objet Excursion à sauvegarder.
     * @return bool True si la mise à jour a réussi, false sinon.
     */
    public function modifier(array $data): ?Excursion
    {
        if ($data['date_creation'] instanceof DateTime) {
            $data['date_creation'] = $data['date_creation']->format('Y-m-d H:i:s');
        }

        $data['public'] = isset($data['public']) && $data['public'] === 'on' ? 1 : 0;

        try {
            $sql = "UPDATE excursion SET capacite = :capacite, nom = :nom, chemin_image = :chemin_image, 
                date_creation = :date_creation, description = :description, public = :public, 
                id_guide = :id_guide WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute([
                ':id' => $data['id'],
                ':capacite' => $data['capacite'],
                ':nom' => $data['nom'],
                ':chemin_image' => $data['chemin_image'],
                ':date_creation' => $data['date_creation'],
                ':description' => $data['description'],
                ':public' => $data['public'],
                ':id_guide' => $data['id_guide']
            ]);

            return $this->findAssoc($data['id']);
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            throw $e;
        }
    }


    /**
     * Supprime une excursion en fonction de son ID.
     * 
     * @param int $id L'ID de l'excursion à supprimer.
     * @return bool True si la suppression a réussi, false sinon.
     */
    public function supprimer(int $id): bool
    {
        $sql = "DELETE FROM excursion WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Récupère une excursion par son ID.
     * 
     * @param int|null $id L'ID de l'excursion à récupérer.
     * @return Excursion|null L'objet Excursion correspondant à l'ID, ou null si l'excursion n'existe pas.
     */
    public function find(?int $id): ?Excursion
    {
        $sql = "SELECT * FROM excursion WHERE id = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute([':id' => $id]);
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "excursion");
        $result = $pdoStatement->fetch();

        return $result ? $this->hydrate($result) : null;
    }

    /**
     * Récupère une excursion par son ID et la retourne sous forme de tableau associatif.
     * 
     * @param int|null $id L'ID de l'excursion.
     * @return Excursion|null L'objet Excursion ou null si l'ID est introuvable.
     */
    public function findAssoc(?int $id): ?Excursion
    {
        $sql = "SELECT * FROM excursion WHERE id = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute([':id' => $id]);
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $pdoStatement->fetch();

        return $result ? $this->hydrate($result) : null;
    }

    /**
     * @brief Récupère toutes les excursions associées au guide dont l'ID est fourni.
     * @param int|null $id
     * @return array|null
     */

    public function findByGuide(?int $id): ?array
    {
        $sql = "SELECT * FROM excursion WHERE id_guide = :id_guide";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute([':id_guide' => $id]);
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $results = $pdoStatement->fetchAll();
        return $results ? $this->hydrateAll($results) : [];
    }







    /**
     * @brief Récupère toutes les excursions publiques ou associées au guide dont l'ID est fourni.
     * @param int|null $id
     * @return array|null
     */

    public function findPublic(?int $id): ?array
    {
        $sql = "SELECT * FROM excursion WHERE public = 1 OR id_guide = :id_guide";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute([':id_guide' => $id]);
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $results = $pdoStatement->fetchAll();
        return $results ? $this->hydrateAll($results) : [];
    }

    /**
     * Récupère toutes les excursions sous forme de tableau associatif.
     * 
     * @return array|null Un tableau de toutes les excursions ou null si aucune excursion n'est trouvée.
     */
    public function findAllAssoc(): ?array
    {
        $sql = "SELECT * FROM excursion";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $pdoStatement->fetchAll();

        return $result;
    }

    /**
     * Crée une instance de Excursion à partir des données récupérées.
     * 
     * @param array $tableauAssoc Tableau associatif contenant les données de l'excursion.
     * @return Excursion L'objet Excursion créé.
     */
    public function hydrate(array $tableauAssoc): ?Excursion
    {
        $excursion = new Excursion();
        $excursion->setId($tableauAssoc['id']);
        $excursion->setCapacite($tableauAssoc['capacite']);
        $excursion->setNom($tableauAssoc['nom']);
        $excursion->setChemin_Image($tableauAssoc['chemin_image']);
        $excursion->setDate_creation(
            !empty($tableauAssoc['date_creation'])
                ? new DateTime($tableauAssoc['date_creation'])
                : null
        );
        $excursion->setDescription($tableauAssoc['description']);
        $excursion->setPublic($tableauAssoc['public']);
        $excursion->setId_Guide($tableauAssoc['id_guide']);

        return $excursion;
    }

    /**
     * Hydrate une liste d'instances de Excursion à partir d'un tableau associatif.
     * 
     * @param array $tableauAssoc Tableau associatif contenant les données.
     * @return array Un tableau d'objets Excursion.
     */
    public function hydrateAll(array $tableauAssoc): ?array
    {
        $excursions = [];
        foreach ($tableauAssoc as $ligne) {
            $excursions[] = $this->hydrate($ligne);
        }
        return $excursions;
    }

    /**
     * Récupère toutes les excursions, triées par date de visite.
     * 
     * @return array Tableau des excursions triées.
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM excursion ORDER BY date_creation DESC";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $excursions = [];
        foreach ($result as $row) {
            $excursions[] = new Excursion(
                $row['id'],
                $row['capacite'],
                $row['nom'],
                new DateTime($row['date_creation']),
                $row['description'],
                $row['chemin_image'],
                $row['public'],
                $row['id_guide']
            );
        }
        return $excursions;
    }

    /**
     * @brief Récupère toutes les excursions, triées par date de visite, avec des engagements existants.
     * @return array
     * @throws DateMalformedStringException
     */
    public function findAllWithExistingEngagement(): array
    {
        $sql = "SELECT DISTINCT e.* 
            FROM excursion e
            INNER JOIN engagement eng ON e.id = eng.id_excursion
            ORDER BY e.date_creation DESC";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $excursions = [];
        foreach ($result as $row) {
            $excursions[] = new Excursion(
                $row['id'],
                $row['capacite'],
                $row['nom'],
                new DateTime($row['date_creation']),
                $row['description'],
                $row['chemin_image'],
                $row['public'],
                $row['id_guide']
            );
        }
        return $excursions;
    }

    /**
     * @brief Récupère toutes les excursions associées à la visite dont l'ID est fourni.
     * @param int|null $idVisite
     * @return array|null
     */
    public function findByVisite(?int $idVisite): ?array
    {
        $query = "SELECT DISTINCT e.* FROM excursion e 
        JOIN composer c ON e.id = c.id_excursion
        JOIN visite v ON c.id_visite = v.id
        INNER JOIN engagement eng ON e.id = eng.id_excursion
        WHERE v.id = :idVisite
        ORDER BY e.date_creation DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':idVisite', $idVisite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
