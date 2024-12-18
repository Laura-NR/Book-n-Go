<?php
class VoyageurDao {
    private ?PDO $pdo;

    // Constructeur de la classe qui initialise la connexion PDO
    public function __construct(?PDO $pdo = null) {
        $this->pdo = bd::getInstance()->getPdo();
    }

    // Getteur
    public function getPdo(){
        return $this->pdo;
    }

    // Setteur
    public function setPdo(?PDO $pdo = null){
        $this->pdo = $pdo;
    }

    // Hydrate les données de la base de données dans l'objet Voyageur
    private function hydrate(Voyageur $voyageur, array $data): void {
        // Remplir les propriétés de l'objet Voyageur à partir du tableau $data
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($voyageur, $method)) {
                $voyageur->$method($value);
            }
        }
    }

    // Recherche un voyageur par son ID
    public function find(?int $id): ?Voyageur {
        $sql = "SELECT * FROM voyageur WHERE id = :id";
        $requete = $this->pdo->prepare($sql);
        $requete->execute(['id' => $id]);
        $data = $requete->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $voyageur = new Voyageur();
            $this->hydrate($voyageur, $data); // Hydrater l'objet avec les données de la BD
            return $voyageur;
        }
        return null;
    }

     // Trouver un voyageur par ID en mode associatif (retourne un tableau associatif ou null)
     public function findAssoc(?int $id): ?array
     {
         $sql = "SELECT * FROM voyageur WHERE id = :id";
         $pdoStatement = $this->pdo->prepare($sql);
         $pdoStatement->execute(['id' => $id]);
         $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
         $result = $pdoStatement->fetch();
         if (!$result) {
             echo "Aucun voyageur trové avec l'id $id";
         }
         return $result ?: null;
     }
 
     // Récupère tous les voyageurs
     public function findAll(): array {
         // Requête SELECT pour récupérer tous les voyageurs
         $sql = "SELECT * FROM voyageur";
         $requete = $this->pdo->prepare($sql); // Préparation de la requête
         $requete->execute(); // Exécution de la requête
         return $requete->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Voyageur'); // Récupération de tous les résultats sous forme d'objets Voyageur
     }

    // Crée un nouveau voyageur
    public function creer(Voyageur $voyageur): bool {
        $sql = "INSERT INTO voyageur (nom, prenom, numero_tel, mail, mdp, derniere_co) 
                VALUES (:nom, :prenom, :numero_tel, :mail, :mdp, :derniere_co)";
        $requete = $this->pdo->prepare($sql);
        return $requete->execute([
            'nom' => $voyageur->getNom(),
            'prenom' => $voyageur->getPrenom(),
            'numero_tel' => $voyageur->getNumeroTel(),
            'mail' => $voyageur->getMail(),
            'mdp' => $voyageur->getMdp(),
            'derniere_co'=> $voyageur->getDerniereCo(),
        ]);
    }

    // Met à jour un voyageur existant
    public function mettreAJour(Voyageur $voyageur): bool {
        $sql = "UPDATE voyageur 
                SET nom = :nom, prenom = :prenom, numero_tel = :numero_tel, mail = :mail, mdp = :mdp 
                WHERE id = :id";
        $requete = $this->pdo->prepare($sql);
        return $requete->execute([
            'nom' => $voyageur->getNom(),
            'prenom' => $voyageur->getPrenom(),
            'numero_tel' => $voyageur->getNumeroTel(),
            'mail' => $voyageur->getMail(),
            'mdp' => password_hash($voyageur->getMdp(), PASSWORD_BCRYPT),
            'id' => $voyageur->getId()

        ]);
    }



    // Supprime un voyageur par son ID
    public function supprimer(int $id): int {
        $sql = "DELETE FROM voyageur WHERE id = :id";
        $requete = $this->pdo->prepare($sql);
        return $requete->execute(['id' => $id]);
    }

    public function majDerniereCo(Voyageur $voyageur) : bool
    {
        $nouvelleCo = $voyageur->getDerniereCo()->format("Y-m-d");
        $sql = "UPDATE voyageur 
                SET derniere_co = :co 
                WHERE id = :id";
        $requete = $this->pdo->prepare($sql);
        return $requete->execute([
            'co' => $nouvelleCo,
            'id' => $voyageur->getId()
        ]);
    }
}
?>