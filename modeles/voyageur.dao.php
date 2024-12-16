<?php
class VoyageurDao {
    private ?PDO $pdo;

    // Constructeur de la classe qui initialise la connexion PDO
    public function __construct(?PDO $pdo = null) {
        $this->pdo = bd::getInstance()->getPdo();
    }

    //Getteur
    public function getPdo(){
        return $this->pdo;
    }

    //Setteur
    public function setPdo(?PDO $pdo = null){
        $this->pdo = $pdo;
    }


    // Recherche un voyageur par son ID
    public function find(?int $id): ?Voyageur {
        // Requête SELECT pour récupérer un voyageur par son ID
        $sql = "SELECT * FROM voyageur WHERE id = :id";
        $requete = $this->pdo->prepare($sql); // Préparation de la requête
        $requete->execute(['id' => $id]); // Exécution avec le paramètre 'id'
        $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Voyageur'); // Récupération du résultat sous forme d'objet Voyageur
        return $requete->fetch() ?: null; // Retourne le résultat ou null si pas trouvé
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
        // Requête INSERT pour ajouter un voyageur
        $sql = "INSERT INTO voyageur (nom, prenom, numero_tel, mail, mdp) 
                VALUES (:nom, :prenom, :numero_tel, :mail, :mdp)";
        $requete = $this->pdo->prepare($sql); // Préparation de la requête
        return $requete->execute([
            'nom' => $voyageur->getNom(),
            'prenom' => $voyageur->getPrenom(),
            'numero_tel' => $voyageur->getNumeroTel(),
            'mail' => $voyageur->getMail(),
            'mdp' => $voyageur->getMdp()
        ]); // Exécution de la requête avec les paramètres du voyageur
    }

    // Met à jour un voyageur existant
    public function mettreAJour(Voyageur $voyageur): bool {
        // Requête UPDATE pour modifier un voyageur
        $sql = "UPDATE voyageur 
                SET nom = :nom, prenom = :prenom, numero_tel = :numero_tel, mail = :mail, mdp = :mdp 
                WHERE id = :id";
        $requete = $this->pdo->prepare($sql); // Préparation de la requête
        return $requete->execute([
            'nom' => $voyageur->getNom(),
            'prenom' => $voyageur->getPrenom(),
            'numero_tel' => $voyageur->getNumeroTel(),
            'mail' => $voyageur->getMail(),
            'mdp' => $voyageur->getMdp(),
            'id' => $voyageur->getId()
        ]); // Exécution de la requête pour mettre à jour le voyageur
    }

    // Supprime un voyageur par son ID
    public function supprimer(int $id): int {
        // Requête DELETE pour supprimer un voyageur
        $sql = "DELETE FROM voyageur WHERE id = :id";
        $requete = $this->pdo->prepare($sql); // Préparation de la requête
        return $requete->execute(['id' => $id]); // Exécution de la requête pour supprimer le voyageur
    }
    // Liste tous les voyageurs
    public function listerTousVoyageurs(): array {
        // Requête SELECT pour récupérer tous les voyageurs
        $sql = "SELECT * FROM voyageur";
        $requete = $this->pdo->prepare($sql); // Préparation de la requête
        $requete->execute(); // Exécution de la requête
        return $requete->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Voyageur'); // Récupère tous les voyageurs sous forme d'objets
    }

}

?>
