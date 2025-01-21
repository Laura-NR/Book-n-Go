<?php
/**
 * @file voyageur.dao.php
 * @class VoyageurDao
 * @brief Classe DAO pour la gestion des voyageurs dans la base de données.
 *
 * La classe `VoyageurDao` fournit des méthodes pour interagir avec les voyageurs dans la base de données.
 */
class VoyageurDao {
    private ?PDO $pdo;

    // Constructeur de la classe qui initialise la connexion PDO
    public function __construct(?PDO $pdo = null) {
        $this->pdo = bd::getInstance()->getPdo();
    }

    // Getteur
    public function getPdo(): ?PDO {
        return $this->pdo;
    }

    // Setteur
    public function setPdo(?PDO $pdo = null): void {
        $this->pdo = $pdo;
    }

    // Hydrate un voyageur à partir d'un tableau associatif
    public function hydrate(Voyageur $voyageur, array $data): void {
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

    // Trouver un voyageur par ID en mode associatif
    public function findAssoc(?int $id): ?array {
        $sql = "SELECT * FROM voyageur WHERE id = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['id' => $id]);
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $pdoStatement->fetch();
        if (!$result) {
            echo "Aucun voyageur trouvé avec l'id $id";
        }
        return $result ?: null;
    }

    // Récupère tous les voyageurs
    public function findAll(): array {
        $sql = "SELECT * FROM voyageur";
        $requete = $this->pdo->prepare($sql);
        $requete->execute();
        return $requete->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Voyageur');
    }

    // Crée un nouveau voyageur
    public function creer(Voyageur $voyageur): bool {
        $sql = "INSERT INTO voyageur (nom, prenom, numero_tel, mail, mdp, derniere_co, tentatives_echouees, date_dernier_echec, statut_compte) 
                VALUES (:nom, :prenom, :numero_tel, :mail, :mdp, :derniere_co, :tentatives_echouees, :date_dernier_echec, :statut_compte)";
        $requete = $this->pdo->prepare($sql);

        $derniereCo = $voyageur->getDerniereCo();
        if ($derniereCo !== null) {
            $derniereCo = $derniereCo->format("Y-m-d");
        }

        return $requete->execute([
            'nom' => $voyageur->getNom(),
            'prenom' => $voyageur->getPrenom(),
            'numero_tel' => $voyageur->getNumeroTel(),
            'mail' => $voyageur->getMail(),
            'mdp' => $voyageur->getMdp(),
            'derniere_co' => $derniereCo,
            'tentatives_echouees' => 0,
            'date_dernier_echec' => null,
            'statut_compte' => 'actif'
        ]);
    }

    // Met à jour un voyageur existant
    public function mettreAJour(Voyageur $voyageur): bool {
        $sql = "UPDATE voyageur 
                SET nom = :nom, prenom = :prenom, numero_tel = :numero_tel, mail = :mail, mdp = :mdp,
                    tentatives_echouees = :tentatives_echouees, date_dernier_echec = :date_dernier_echec, statut_compte = :statut_compte
                WHERE id = :id";
        $requete = $this->pdo->prepare($sql);

        return $requete->execute([
            'nom' => $voyageur->getNom(),
            'prenom' => $voyageur->getPrenom(),
            'numero_tel' => $voyageur->getNumeroTel(),
            'mail' => $voyageur->getMail(),
            'mdp' => password_hash($voyageur->getMdp(), PASSWORD_BCRYPT),
            'tentatives_echouees' => $voyageur->getTentativesEchouees(),
            'date_dernier_echec' => $voyageur->getDateDernierEchec() ? $voyageur->getDateDernierEchec()->format('Y-m-d') : null,
            'statut_compte' => $voyageur->getStatutCompte(),
            'id' => $voyageur->getId()
        ]);
    }

    // Supprime un voyageur par son ID
    public function supprimer(int $id): int {
        $sql = "DELETE FROM voyageur WHERE id = :id";
        $requete = $this->pdo->prepare($sql);
        return $requete->execute(['id' => $id]);
    }

    // Mettre à jour la dernière connexion d'un voyageur
    public function majDerniereCo(Voyageur $voyageur): bool {
        $derniereCo = $voyageur->getDerniereCo();
        if ($derniereCo instanceof DateTime) {
            $nouvelleCo = $voyageur->getDerniereCo()->format("Y-m-d");
            $sql = "UPDATE voyageur 
                    SET derniere_co = :co 
                    WHERE id = :id";
            $requete = $this->pdo->prepare($sql);
            return $requete->execute([
                'co' => $nouvelleCo,
                'id' => $voyageur->getId()
            ]);
        } else {
            error_log("Error in majDerniereCo: derniere_co is null for Voyageur ID: " . $voyageur->getId());
            return false;
        }
    }

//    //Incremente le nombre de tentative d'un voyageur
//    public function incrementeTentatives(Voyageur $voyageur): void {
//        // Incrémente les tentatives échouées dans l'objet avant mise à jour en base de données
//        $tentativesActuelles = $voyageur->getTentativesEchouees();
//        $voyageur->setTentativesEchouees($tentativesActuelles + 1);
//
//        // Prépare et exécute la requête SQL
//        $stmt = $this->pdo->prepare("
//        UPDATE voyageur
//        SET tentatives_echouees = :tentatives_echouees
//        WHERE id = :id
//    ");
//
//        $stmt->execute([
//            'tentatives_echouees' => $voyageur->getTentativesEchouees(),
//            'id' => $voyageur->getId()
//        ]);
//    }




    // Met à jour le statut du compte d'un voyageur
//    public function majStatutCompte(Voyageur $voyageur): void {
//        $stmt = $this->pdo->prepare("
//            UPDATE voyageur
//            SET tentatives_echouees = :tentatives_echouees,
//                date_dernier_echec = :date_dernier_echec,
//                statut_compte = :statut_compte
//            WHERE id = :id
//        ");
//
//        $stmt->execute([
//            'tentatives_echouees' => $voyageur->getTentativesEchouees(),
//            'date_dernier_echec' => $voyageur->getDateDernierEchec() ? $voyageur->getDateDernierEchec()->format('Y-m-d H:i:s') : null,
//            'statut_compte' => $voyageur->getStatutCompte(),
//            'id' => $voyageur->getId()
//        ]);
//
//    }
//
//    // Calculer le temps restant avant réactivation du compte
//    public function tempsRestantAvantReactivationCompte(Voyageur $voyageur): ?DateInterval {
//        $dateDernierEchec = $voyageur->getDateDernierEchec();
//        if (!$dateDernierEchec) {
//            return null;
//        }
//
//        $delaiReactivation = clone $dateDernierEchec;
//        $delaiReactivation->modify('+15 minutes');
//
//        $maintenant = new DateTime();
//        if ($maintenant >= $delaiReactivation) {
//            return null; // Pas de temps restant
//        }
//
//        return $delaiReactivation->diff($maintenant);
//    }
//
//    // Réinitialiser les tentatives échouées d'un voyageur
//    public function reinitialiserTentatives(Voyageur $voyageur): bool {
//        $sql = "UPDATE voyageur SET tentatives_echouees = 0 WHERE id = :id";
//        $requete = $this->pdo->prepare($sql);
//        return $requete->execute(['id' => $voyageur->getId()]);
//    }

}
?>
