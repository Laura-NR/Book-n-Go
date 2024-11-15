<?php
class ExcursionDao {
    private ?PDO $pdo;

    public function __construct(PDO $pdo = null) {
        $this->pdo = bd::getInstance()->getPdo();
        var_dump($this->pdo);
    }

    // Créer une nouvelle visite
    public function creer(array $data): ?Excursion {
        $sql = "INSERT INTO excursion (capacite, nom, chemin_image, date_visite, description, public, id_guide)
                VALUES (:capacite, :nom, :chemin_image, :date_visite, :description, :public, :id_guide)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':capacite' => $data['capacite'],
            ':nom' => $data['nom'],
            ':chemin_image' => $data['chemin_image'],
            ':date_visite' => $data['date_visite'],
            ':description' => $data['description'],
            ':public' => $data['public'],
            ':id_guide' => $data['id_guide']
        ]);

        // Récupère l'id de la nouvelle excursion insérée et retourne l'objet Excursion hydraté
        return $this->find($this->pdo->lastInsertId());
    }

    // Sauvegarde une excursion existante (mise à jour)
    public function sauvegarder(Excursion $excursion): bool {
        $sql = "UPDATE excursion SET capacite = :capacite, nom = :nom, chemin_image = :chemin_image, date_visite = :date_visite,
                description = :description, public = :public, id_guide = :id_guide WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $excursion->getId(),
            ':capacite' => $excursion->getCapacite(),
            ':nom' => $excursion->getNom(),
            ':chemin_image' => $excursion->getChemin_Image(),
            ':date_excursion' => $excursion->getDate_Visite(),
            ':description' => $excursion->getDescription(),
            ':public' => $excursion->getPublic(),
            ':id_guide' => $excursion->getId_Guide()
        ]);
    }

    // Supprime une excursion par ID
    public function supprimer(int $id): bool {
        $sql = "DELETE FROM excursion WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Récupère une excursion par ID
    public function find(?int $id): ?Excursion {
        $sql = "SELECT * FROM excursion WHERE id = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute([':id' => $id]);
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $pdoStatement->fetch();

        return $result ? $this->hydrate($result) : null;
    }

    // Récupère toutes les excursions
    public function findAll(): ?array {
        $sql = "SELECT * FROM excursion";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $pdoStatement->fetchAll();

        return $this->hydrateAll($result);
    }

    // Crée une instance de Excursion avec les données récupérées
    public function hydrate(array $tableauAssoc): ?Excursion {
        $excursion = new Excursion();
        $excursion->setId($tableauAssoc['id']);
        $excursion->setCapacite($tableauAssoc['capacite']);
        $excursion->setNom($tableauAssoc['nom']);
        $excursion->setChemin_Image($tableauAssoc['chemin_image']);
        $excursion->setDate_Visite($tableauAssoc['date_visite']);
        $excursion->setDescription($tableauAssoc['description']);
        $excursion->setPublic($tableauAssoc['public']);
        $excursion->setId_Guide($tableauAssoc['id_guide']);
        
        return $excursion;
    }

    // Hydrate une liste d'instances de Excursion
    public function hydrateAll(array $tableauAssoc): ?array {
        $excursions = [];
        foreach ($tableauAssoc as $ligne) {
            $excursions[] = $this->hydrate($ligne);
        }
        return $excursions;
    }
}
?>
