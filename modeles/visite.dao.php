<?php
class VisiteDao {
    private ?PDO $pdo;

    public function __construct(PDO $pdo = null) {
        $this->pdo = $pdo;
    }

    // Créer une nouvelle visite
    public function creer(array $data): ?Visite {
        $sql = "INSERT INTO visite (capacite, nom, chemin_image, date_visite, description, public, id_guide)
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

        // Récupère l'id de la nouvelle visite insérée et retourne l'objet Visite hydraté
        return $this->find($this->pdo->lastInsertId());
    }

    // Sauvegarde une visite existante (mise à jour)
    public function sauvegarder(Visite $visite): bool {
        $sql = "UPDATE visite SET capacite = :capacite, nom = :nom, chemin_image = :chemin_image, date_visite = :date_visite,
                description = :description, public = :public, id_guide = :id_guide WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $visite->getId(),
            ':capacite' => $visite->getCapacite(),
            ':nom' => $visite->getNom(),
            ':chemin_image' => $visite->getCheminImage(),
            ':date_visite' => $visite->getDateVisite(),
            ':description' => $visite->getDescription(),
            ':public' => $visite->getPublic(),
            ':id_guide' => $visite->getIdGuide()
        ]);
    }

    // Supprime une visite par ID
    public function supprimer(int $id): bool {
        $sql = "DELETE FROM visite WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Récupère une visite par ID
    public function find(?int $id): ?Visite {
        $sql = "SELECT * FROM visite WHERE id = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute([':id' => $id]);
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $pdoStatement->fetch();

        return $result ? $this->hydrate($result) : null;
    }

    // Récupère toutes les visites
    public function findAll(): ?array {
        $sql = "SELECT * FROM visite";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $pdoStatement->fetchAll();

        return $this->hydrateAll($result);
    }

    // Crée une instance de Visite avec les données récupérées
    public function hydrate(array $tableauAssoc): ?Visite {
        $visite = new Visite();
        $visite->setId($tableauAssoc['id']);
        $visite->setCapacite($tableauAssoc['capacite']);
        $visite->setNom($tableauAssoc['nom']);
        $visite->setCheminImage($tableauAssoc['chemin_image']);
        $visite->setDateVisite($tableauAssoc['date_visite']);
        $visite->setDescription($tableauAssoc['description']);
        $visite->setPublic($tableauAssoc['public']);
        $visite->setIdGuide($tableauAssoc['id_guide']);
        
        return $visite;
    }

    // Hydrate une liste d'instances de Visite
    public function hydrateAll(array $tableauAssoc): ?array {
        $visites = [];
        foreach ($tableauAssoc as $ligne) {
            $visites[] = $this->hydrate($ligne);
        }
        return $visites;
    }
}
?>
