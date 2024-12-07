<?php

class UtilisateurDao {
    private ?PDO $pdo;

    // Constructeur de la classe qui initialise la connexion PDO
    public function __construct(?PDO $pdo = null) {
        $this->pdo = $pdo;
    }

    // Getter pour l'objet PDO
    public function getPdo(): ?PDO {
        return $this->pdo;
    }

    // Setter pour l'objet PDO
    public function setPdo($pdo): void {
        $this->pdo = $pdo;
    }

    // Trouver un utilisateur par son ID (retourne Guide ou Voyageur selon le cas)
    public function find(int $id): ?Utilisateur {
        $sql = "SELECT * FROM utilisateur WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Utilisateur');
        $utilisateur = $stmt->fetch();

        if ($utilisateur) {
            // Vérifier si l'utilisateur est un Guide en fonction de la présence de chemin_certif
            if (!empty($utilisateur->chemin_certif)) {
                // Si chemin_certif existe, l'utilisateur est un Guide
                $guide = new Guide();
                $guide->setId($utilisateur->getId());
                $guide->setNom($utilisateur->getNom());
                $guide->setPrenom($utilisateur->getPrenom());
                $guide->setNumeroTel($utilisateur->getNumeroTel());
                $guide->setMail($utilisateur->getMail());
                $guide->setMdp($utilisateur->getMdp());
                $guide->setCheminCertification($utilisateur->chemin_certif);
                return $guide;
            }
            // Sinon, c'est un Voyageur
            return $utilisateur;
        }
        return null;
    }

    // Recherche un voyageur par son ID
    public function findVoyageur(int $id): ?Voyageur {
        $sql = "SELECT * FROM voyageur WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Voyageur');
        return $stmt->fetch() ?: null;
    }

    // Récupère tous les voyageurs
    public function findAllVoyageurs(): array {
        $sql = "SELECT * FROM voyageur";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Voyageur');
    }

    // Créer un utilisateur (Guide ou Voyageur)
    public function creer(Utilisateur $utilisateur): bool {
        $sql = "INSERT INTO utilisateur (nom, prenom, numero_tel, mail, mdp, chemin_certif) 
                VALUES (:nom, :prenom, :numero_tel, :mail, :mdp, :chemin_certif)";
        $stmt = $this->pdo->prepare($sql);

        // Vérifier si l'utilisateur est un Guide en fonction de la présence de chemin_certif
        $cheminCertif = null;
        /* if (!empty($utilisateur->getCheminCertification())) {
            $cheminCertif = $utilisateur->getCheminCertification();
        } */

        return $stmt->execute([
            'nom' => $utilisateur->getNom(),
            'prenom' => $utilisateur->getPrenom(),
            'numero_tel' => $utilisateur->getNumeroTel(),
            'mail' => $utilisateur->getMail(),
            'mdp' => $utilisateur->getMdp(),
            'chemin_certif' => $cheminCertif
        ]);
    }

    // Mettre à jour un utilisateur (Guide ou Voyageur)
    public function mettreAJour(Utilisateur $utilisateur): bool {
        $sql = "UPDATE utilisateur SET nom = :nom, prenom = :prenom, numero_tel = :numero_tel, mail = :mail, mdp = :mdp, chemin_certif = :chemin_certif WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);

        // Vérifier si l'utilisateur est un Guide en fonction de la présence de chemin_certif
        $cheminCertif = null;
        /* if (!empty($utilisateur->getCheminCertification())) {
            $cheminCertif = $utilisateur->getCheminCertification();
        } */

        return $stmt->execute([
            'id' => $utilisateur->getId(),
            'nom' => $utilisateur->getNom(),
            'prenom' => $utilisateur->getPrenom(),
            'numero_tel' => $utilisateur->getNumeroTel(),
            'mail' => $utilisateur->getMail(),
            'mdp' => $utilisateur->getMdp(),
            'chemin_certif' => $cheminCertif
        ]);
    }

    // Supprimer un utilisateur
    public function supprimer(int $id): bool {
        $sql = "DELETE FROM utilisateur WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // Lister tous les utilisateurs
    public function listerTous(): array {
        $sql = "SELECT * FROM utilisateur";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Utilisateur');
        $utilisateurs = $stmt->fetchAll();

        // Transformer en Guide si nécessaire
        foreach ($utilisateurs as &$utilisateur) {
            // Vérifier si l'utilisateur a un chemin_certif
            if (!empty($utilisateur->chemin_certif)) {
                // Si l'utilisateur a un chemin_certif, c'est un Guide
                $guide = new Guide();
                $guide->setId($utilisateur->getId());
                $guide->setNom($utilisateur->getNom());
                $guide->setPrenom($utilisateur->getPrenom());
                $guide->setNumeroTel($utilisateur->getNumeroTel());
                $guide->setMail($utilisateur->getMail());
                $guide->setMdp($utilisateur->getMdp());
                $guide->setCheminCertification($utilisateur->chemin_certif);
                $utilisateur = $guide;
            }
        }

        return $utilisateurs;
    }
}

?>
