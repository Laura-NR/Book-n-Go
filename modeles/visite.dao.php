<?php
class VisiteDao{
    private ?PDO $pdo;

     function __construct(PDO $pdo = null) {
        $this->pdo = $pdo;
    }

     public function getPdo(): ?PDO
     {
          return $this->pdo;
     }

     public function setPdo($pdo):void
     {
          $this->pdo = $pdo;

     }
     //  obtenir les informations concernant une visites en particulier
     public function find(?int $id): ?Visite
     {
         $sql = "SELECT * FROM visite WHERE id = :id";
         $pdoStatement = $this->pdo->prepare($sql);
         $pdoStatement->execute(array(':id' => $id));
         $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
         $visite = $pdoStatement->fetch();
         return $visite;
     }
    //  obtenir toutes les informations de la table `visite`
     public function findAll(): ?array
     {
        $sql="SELECT * FROM visite";
        $pdostatement= $this->pdo->prepare($sql);// Préparation de la requête SQL
        $pdostatement->execute();// Exécution de la requête
        //Définir le mode de récupération des résultats comme un tableau associatif
        $pdostatement->setFetchMode(PDO::FETCH_ASSOC);
        //Récupération de toutes les lignes de résultats sous forme de tableau
        $visite=$pdostatement->fetchAll();
        return $visite;
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

// Sauvegarde une visite existante en la mettant à jour
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

// Supprime une visite en fonction de son identifiant
public function supprimer(int $id): bool {
    $sql = "DELETE FROM visite WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    
    return $stmt->execute([':id' => $id]);
}
//permet de remplir la nvl instance de Visite avec les données recup dans la bd 
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

