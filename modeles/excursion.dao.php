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
            $sql = "INSERT INTO excursion (capacite, nom, chemin_image, date_creation, description, public, id_utilisateur)
                VALUES (:capacite, :nom, :chemin_image, :date_creation, :description, :public, :id_utilisateur)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':capacite' => $data['capacite'],
                ':nom' => $data['nom'],
                ':chemin_image' => $data['chemin_image'],
                ':date_creation' => $data['date_creation'],
                ':description' => $data['description'],
                ':public' => $data['public'],
                ':id_utilisateur' => $data['id_utilisateur']
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
    public function sauvegarder(Excursion $excursion): bool
    {
        $sql = "UPDATE excursion SET capacite = :capacite, nom = :nom, chemin_image = :chemin_image, date_creation = :date_creation,
                description = :description, public = :public, id_utilisateur = :id_utilisateur WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $excursion->getId(),
            ':capacite' => $excursion->getCapacite(),
            ':nom' => $excursion->getNom(),
            ':chemin_image' => $excursion->getChemin_Image(),
            ':date_creation' => $excursion->getDate_creation(),
            ':description' => $excursion->getDescription(),
            ':public' => $excursion->getPublic(),
            ':id_utilisateur' => $excursion->getId_utilisateur()
        ]);
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
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS| PDO::FETCH_PROPS_LATE,"excursion");
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
        $excursion->setId_utilisateur($tableauAssoc['id_utilisateur']);

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
                $row['id_utilisateur']
            );
        }
        return $excursions;
    }
}
