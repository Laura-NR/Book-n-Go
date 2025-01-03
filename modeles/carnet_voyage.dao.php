<?php

class CarnetVoyageDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo=null)
    {
        $this->pdo = bd::getInstance()->getPdo();
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM carnet_voyage";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $carnets = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        return $carnets;
    }

    public function findAllByIdVoyageur(int $idVoyageur): array
    {
        $sql = "SELECT * FROM carnet_voyage WHERE id_voyageur = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array(':id' => $idVoyageur));
        $carnets = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        return $carnets;
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

    public function findAssoc(?int $id): ?CarnetVoyage
    {
        $sql = "SELECT * FROM carnet_voyage WHERE id = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array(':id' => $id));
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $carnet = $pdoStatement->fetch();
        return $carnet;
    }

    public function find(?int $id): ?CarnetVoyage
    {
        $sql = "SELECT * FROM carnet_voyage WHERE id = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array(':id' => $id));
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'CarnetVoyage');
        $carnet = $pdoStatement->fetch();
        return $carnet;
    }

    public function hydrate(array $tableauAssoc): ?CarnetVoyage
    {
        $carnet = new CarnetVoyage();
        $carnet->setId($tableauAssoc['id']);
        $carnet->setTitre($tableauAssoc['titre']);
        $carnet->setChemin_img($tableauAssoc['chemin_img']);
        $carnet->setDescription($tableauAssoc['description']);
        $carnet->setVoyageur($tableauAssoc['id_voyageur']);
        return $carnet;
    }

    public function hydrateAll(array $tableauAssoc): ?array
    {
        $carnets = [];
        foreach ($tableauAssoc as $ligne) {
            $carnet = new CarnetVoyage();
            $carnet = $this->hydrate($ligne);
            $carnets[] = $carnet;
        }
        return $carnets;
    }

    public function inserer(array $data): bool
    {
        $sql = "INSERT INTO carnet_voyage (titre, chemin_img, description, id_voyageur) 
                VALUES (:titre, :chemin_img, :description, :id_voyageur)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':titre', $data['titre']);
        $stmt->bindValue(':chemin_img', $data['chemin_img']);
        $stmt->bindValue(':description', $data['description']);
        $stmt->bindValue(':id_voyageur', $data['id_voyageur']);

        try {
            $result = $stmt->execute();
            return $result; // Retourne true si succès
        } catch (PDOException $e) {
            // dans le cas d'erreurs -> les faire remonter
            echo "Erreur lors de l'insertion du carnet: " . $e->getMessage();
            return false;
        }
    }
}