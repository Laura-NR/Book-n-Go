<?php

class GuideDao
{
    private ?PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo;
    }

    // Getters et Setters
    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }

    public function setPdo(?PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    // Trouver un guide par ID (retourne un objet Guide ou null)
    public function find(?int $id): ?Guide
    {
        $sql = "SELECT * FROM guide WHERE id = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['id' => $id]);
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC); // Fetch as associative array
        $data = $pdoStatement->fetch();

        if ($data) {
            return $this->hydrate($data); // Hydrate a Guide object
        } else {
            return null;
        }
    }

    // Trouver tous les guides (retourne un tableau d'objets Guide)
    public function findAll(): array
    {
        $sql = "SELECT * FROM guide";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Guide');
        return $pdoStatement->fetchAll() ?: []; // Retourne un tableau vide si aucun résultat trouvé
    }

    // Trouver un guide par ID en mode associatif (retourne un tableau associatif ou null)
    public function findAssoc(?int $id): ?array
    {
        $sql = "SELECT * FROM guide WHERE id = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['id' => $id]);
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $pdoStatement->fetch();
        if (!$result) {
            echo "Aucun guide trové avec l'id $id";
        }
        return $result ?: null;
    }

    // Trouver tous les guides en mode associatif (retourne un tableau de tableaux associatifs)
    public function findAllAssoc(): array
    {
        $sql = "SELECT * FROM guide";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        return $pdoStatement->fetchAll() ?: []; // Retourne un tableau vide si aucun résultat trouvé
    }

    // Hydrater un guide à partir d'un tableau associatif
    public function hydrate(array $data): Guide // No need for nullable return type here
    {
        $guide = new Guide();
        // Use setters inherited from Voyageur
        $guide->setId($data['id']);
        $guide->setNom($data['nom']);
        $guide->setPrenom($data['prenom']);
        $guide->setNumeroTel($data['numero_tel']);
        $guide->setMail($data['mail']);
        $guide->setMdp($data['mdp']);
        // Guide-specific property
        $guide->setCheminCertification($data['chemin_certif']);
        //$guide->setDerniereCo($data['derniere_co']); conversion a faire
        return $guide;
    }

    // Hydrater un tableau de guides à partir de tableaux associatifs
    public function hydrateAll(array $data): array
    {
        return array_map([$this, 'hydrate'], $data); // Utilisation de array_map pour simplifier
    }

    // Créer un guide dans la base de données
    public function creer(Guide $guide): bool
    {
        // Requête SQL incluant le champ 'derniere_co'
        $sql = "INSERT INTO guide (nom, prenom, numero_tel, mail, mdp, chemin_certif, derniere_co) 
                VALUES (:nom, :prenom, :numero_tel, :mail, :mdp, :chemin_certif, :derniere_co)";
        
        $pdoStatement = $this->pdo->prepare($sql);
        
        // Vérifier si la dernière connexion est définie et convertir au format voulu
        $derniereCo = $guide->getDerniereCo();
        if ($derniereCo !== null) {
            $derniereCo = $derniereCo->format("Y-m-d");  // Format de la date
        } else {
            $derniereCo = null; // Si la date n'est pas définie, mettre à null
        }
    
        // Exécution de la requête avec les paramètres
        return $pdoStatement->execute([
            'nom' => $guide->getNom(),
            'prenom' => $guide->getPrenom(),
            'numero_tel' => $guide->getNumeroTel(),
            'mail' => $guide->getMail(),
            'mdp' => $guide->getMdp(),
            'chemin_certif' => $guide->getCheminCertification(),
            'derniere_co' => $derniereCo,  // Paramètre de la date de dernière connexion
        ]);
    }
    

    // Mettre à jour un guide dans la base de données
    public function maj(Guide $guide): bool
    {
        $sql = "UPDATE guide SET nom = :nom, prenom = :prenom, numero_tel = :numero_tel, 
                mail = :mail, chemin_certif = :chemin_certif WHERE id = :id";

        // Préparation de la requête
        $pdoStatement = $this->pdo->prepare($sql);

        // Paramètres à exécuter
        $modifications = [
            'id' => $guide->getId(),
            'nom' => $guide->getNom(),
            'prenom' => $guide->getPrenom(),
            'numero_tel' => $guide->getNumeroTel(),
            'mail' => $guide->getMail(),
            'chemin_certif' => "/test/test.png"/*$guide->getCheminCertification()*/ //la certif ne peux pas etre modifier
        ];

        // Exécution de la requête
        return $pdoStatement->execute($modifications);

    }

    // Supprimer un guide de la base de données
    public function supprimer(int $id): bool
    {
        $sql = "DELETE FROM guide WHERE id = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        return $pdoStatement->execute(['id' => $id]);
    }

    // Lister tous les guides (méthode correcte)
    public function listerTousGuides(): array
    {
        $sql = "SELECT * FROM guide";
        $requete = $this->pdo->prepare($sql);
        $requete->execute();
        return $requete->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Guide');
    }

    // Récupérer le chemin du certificat d'un guide par ID
    public function getCheminCertificatParId(int $id): ?string
    {
        $sql = 'SELECT chemin_certif FROM guide WHERE id = :id';
        $requetePrepared = $this->pdo->prepare($sql);
        $requetePrepared->bindParam(':id', $id, PDO::PARAM_INT);
        $requetePrepared->execute();

        if ($guide = $requetePrepared->fetch(PDO::FETCH_ASSOC)) {
            $cheminCertificat = $guide['chemin_certif'];
            // Vérifier si le fichier existe
            if (file_exists($cheminCertificat)) {
                return $cheminCertificat;
            }
        }

        return null;
    }

    public function majDerniereCo(Guide $guide) : bool
    {
        $nouvelleCo = $guide->getDerniereCo()->format("Y-m-d");
        $sql = "UPDATE guide SET derniere_co = :co WHERE id = :id";
        $requete = $this->pdo->prepare($sql);
        return $requete->execute([
            'co' => $nouvelleCo,
            'id' => $guide->getId()
        ]);
    }
}