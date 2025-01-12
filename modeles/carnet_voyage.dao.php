<?php
/**
 * @file carnet_voyage.dao.php
 * @class CarnetVoyageDAO
 * @brief Classe de DAO (Data Access Object) pour les carnets de voyage.
 *
 * La classe `CarnetVoyageDAO` fournit des méthodes pour interagir avec les carnets de voyage dans la base de données.
 * Elle gère les opérations suivantes :
 * - Récupération de tout les carnets de voyage.
 * - Recherche d'un carnet de voyage par son identifiant.
 */
class CarnetVoyageDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo=null)
    {
        $this->pdo = bd::getInstance()->getPdo();
    }

    /**
     * @return array -> tableau des carnets de voyage
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM carnet_voyage";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $carnets = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        return $carnets;
    }

    /**
     * @param int $idVoyageur -> id du voyageur a qui appartient le carnet
     * @return array -> tableau des carnets d'un voyageur
     */
    public function findAllByIdVoyageur(int $idVoyageur): array
    {
        $sql = "SELECT * FROM carnet_voyage WHERE id_voyageur = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array(':id' => $idVoyageur));
        $carnets = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        return $carnets;
    }

    /**
     * Renvoie le pdo
     */ 
    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }

    /**
     * attribue le pdo
     */ 
    public function setPdo(?PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    /**
     * @param int|null $id id du carnet a trouver
     * @return CarnetVoyage|null renvoie un array des infos du carnet dont l'id est passé en parametre
     */
    public function findAssoc(?int $id): ?CarnetVoyage
    {
        $sql = "SELECT * FROM carnet_voyage WHERE id = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array(':id' => $id));
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $carnet = $pdoStatement->fetch();
        return $carnet;
    }

    /**
     * @param int|null $id id du carnet a trouver
     * @return CarnetVoyage|null renvoie un objet CarnetVoyage dont l'id est passé en parametre
     */
    public function find(?int $id): ?CarnetVoyage
    {
        $sql = "SELECT * FROM carnet_voyage WHERE id = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array(':id' => $id));
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'CarnetVoyage');
        $carnet = $pdoStatement->fetch();
        return $carnet;
    }

    /**
     * @param array $tableauAssoc array contenant les informations du carnet
     * @return CarnetVoyage|null renvoie un objet CarnetVoyage
     */
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

    /**
     * @param array $tableauAssoc array contenant les informations des carnets
     * @return array|null renvoie un array contenant des objets CarnetVoyage
     */
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

    /**
     * @param array $data array contenant les informations du carnet à insérer
     * @return bool renvoie true si l'insertion a fonctionné
     */
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