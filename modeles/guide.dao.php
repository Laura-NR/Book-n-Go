<?php
/**
 * @file guide.dao.php
 * @class GuideDao
 * @brief Classe d'accès aux données (DAO) pour les guides.
 *
 * Elle permet de créer, lire, mettre à jour et supprimer des guides.
 * Elle fournit également des méthodes pour récupérer un guide spécifique ou tous les guides.
 */
class GuideDao
{
    private ?PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo;
    }

    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }

    public function setPdo(?PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    /**
     * @brief Trouver un guide par ID (retourne un objet Guide ou null)
     * @param int|null $id
     * @return Guide|null
     */
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

    /**
     * @brief Trouver tous les guides (retourne un tableau d'objets Guide)
     * @return array
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM guide";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Guide');
        return $pdoStatement->fetchAll() ?: []; // Retourne un tableau vide si aucun résultat trouvé
    }

    /**
     * @brief Hydrater un guide à partir d'un tableau associatif
     * @param array $data
     * @return Guide
     */
    public function hydrate(array $data): Guide
    {
        $guide = new Guide();
        // Use setters inherited from Voyageur
        $guide->setId($data['id']);
        $guide->setNom($data['nom']);
        $guide->setPrenom($data['prenom']);
        $guide->setNumeroTel($data['numero_tel']);
        $guide->setMail($data['mail']);
        $guide->setMdp($data['mdp']);
        $guide->setCheminCertification($data['chemin_certif']);

        // Hydrater les nouveaux attributs (tentatives échouées, date de dernier échec, statut compte)
        $guide->setTentativesEchouees($data['tentatives_echouees']);
        $guide->setDateDernierEchec($data['date_dernier_echec'] ? new DateTime($data['date_dernier_echec']) : null);
        $guide->setStatutCompte($data['statut_compte']);

        // Retourner l'objet Guide hydraté
        return $guide;
    }

    /**
     * @brief Créer un guide dans la base de données
     * @param Guide $guide
     * @return bool
     */
    public function creer(Guide $guide): bool
    {
        // Requête SQL incluant tous les nouveaux champs
        $sql = "INSERT INTO guide (nom, prenom, numero_tel, mail, mdp, chemin_certif, tentatives_echouees, date_dernier_echec, statut_compte) 
                VALUES (:nom, :prenom, :numero_tel, :mail, :mdp, :chemin_certif, :tentatives_echouees, :date_dernier_echec, :statut_compte)";

        $pdoStatement = $this->pdo->prepare($sql);

        // Vérification et conversion de la date du dernier échec si elle est définie
        $dateDernierEchec = $guide->getDateDernierEchec();
        $dateDernierEchec = $dateDernierEchec ? $dateDernierEchec->format("Y-m-d") : null;

        // Exécution de la requête avec les paramètres
        return $pdoStatement->execute([
            'nom' => $guide->getNom(),
            'prenom' => $guide->getPrenom(),
            'numero_tel' => $guide->getNumeroTel(),
            'mail' => $guide->getMail(),
            'mdp' => $guide->getMdp(),
            'chemin_certif' => $guide->getCheminCertification(),
            'tentatives_echouees' => $guide->getTentativesEchouees(),
            'date_dernier_echec' => $dateDernierEchec,
            'statut_compte' => $guide->getStatutCompte(),
        ]);
    }

    /**
     * @brief Mettre à jour un guide dans la base de données
     * @param Guide $guide
     * @return bool
     */
    public function maj(Guide $guide): bool
    {
        // Requête SQL incluant les nouveaux champs
        $sql = "UPDATE guide SET nom = :nom, prenom = :prenom, numero_tel = :numero_tel, 
                mail = :mail, chemin_certif = :chemin_certif, tentatives_echouees = :tentatives_echouees, 
                date_dernier_echec = :date_dernier_echec, statut_compte = :statut_compte WHERE id = :id";

        // Préparation de la requête
        $pdoStatement = $this->pdo->prepare($sql);

        // Conversion de la date du dernier échec
        $dateDernierEchec = $guide->getDateDernierEchec();
        $dateDernierEchec = $dateDernierEchec ? $dateDernierEchec->format("Y-m-d") : null;

        // Paramètres à exécuter
        $modifications = [
            'id' => $guide->getId(),
            'nom' => $guide->getNom(),
            'prenom' => $guide->getPrenom(),
            'numero_tel' => $guide->getNumeroTel(),
            'mail' => $guide->getMail(),
            'chemin_certif' => $guide->getCheminCertification(),
            'tentatives_echouees' => $guide->getTentativesEchouees(),
            'date_dernier_echec' => $dateDernierEchec,
            'statut_compte' => $guide->getStatutCompte(),
        ];

        // Exécution de la requête
        return $pdoStatement->execute($modifications);
    }

    /**
     * @brief Supprimer un guide de la base de données
     * @param int $id
     * @return bool
     */
    public function supprimer(int $id): bool
    {
        $sql = "DELETE FROM guide WHERE id = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        return $pdoStatement->execute(['id' => $id]);
    }

    /**
     * @brief Lister tous les guides (méthode correcte)
     * @return array
     */
    public function listerTousGuides(): array
    {
        $sql = "SELECT * FROM guide";
        $requete = $this->pdo->prepare($sql);
        $requete->execute();
        return $requete->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Guide');
    }

    /**
     * @brief Récupérer le chemin du certificat d'un guide par ID
     * @param int $id
     * @return string|null
     */
    public function getCheminCertificatParId(int $id): ?string
    {
        $sql = 'SELECT chemin_certif FROM guide WHERE id = :id';
        $requetePrepared = $this->pdo->prepare($sql);
        $requetePrepared->bindParam(':id', $id, PDO::PARAM_INT);
        $requetePrepared->execute();
        if ($guide = $requetePrepared->fetch(PDO::FETCH_ASSOC)) {
            $cheminCertificat = $guide['chemin_certif'];
            // Vérifier si le fichier existe
            return $cheminCertificat;
        }
        else{
            return null;
        }
    }

    /**
     * @brief Mettre à jour la dernière connexion d'un guide dans la BD
     * @param Guide $guide
     * @return bool
     */
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

    public function majStatutCompte(Guide $guide): void {
        $stmt = $this->pdo->prepare("
        UPDATE guide 
        SET tentativesEchouees = :tentatives_echouees,
            dateDernierEchec = :date_dernier_echec,
            statutCompte = :statut_compte
        WHERE id = :id
    ");
        $stmt->execute([
            'tentatives_echouees' => $guide->getTentativesEchouees(),
            'date_dernier_echec' => $guide->getDateDernierEchec()?->format('Y-m-d H:i:s'),
            'statut_compte' => $guide->getStatutCompte(),
            'id' => $guide->getId(),
        ]);
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


}
?>
