<?php

class VoyageurDao {
    private ?PDO $pdo;

    public function __construct(?PDO $pdo = null) {
        $this->pdo = $pdo;
    }

    public function getPdo(): ?PDO {
        return $this->pdo;
    }

    public function setPdo($pdo): void {
        $this->pdo = $pdo;
    }

    public function find(?int $id): ?Voyageur {
        $sql = "SELECT * FROM voyageur WHERE id = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(['id' => $id]);
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Voyageur');
        return $pdoStatement->fetch() ?: null;
    }

    public function findAll(): array {
        $sql = "SELECT * FROM voyageur";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        return $pdoStatement->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Voyageur');
    }

    public function creer(Voyageur $voyageur): bool {
        $sql = "INSERT INTO voyageur (nom, prenom, numero_tel, mail, mdp) VALUES (:nom, :prenom, :numero_tel, :mail, :mdp)";
        $pdoStatement = $this->pdo->prepare($sql);
        return $pdoStatement->execute([
            'nom' => $voyageur->getNom(),
            'prenom' => $voyageur->getPrenom(),
            'numero_tel' => $voyageur->getNumeroTel(),
            'mail' => $voyageur->getMail(),
            'mdp' => $voyageur->getMdp()
        ]);
    }

    public function maj(Voyageur $voyageur): bool {
        $sql = "UPDATE voyageur SET nom = :nom, prenom = :prenom, numero_tel = :numero_tel, mail = :mail, mdp = :mdp WHERE id = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        return $pdoStatement->execute([
            'nom' => $voyageur->getNom(),
            'prenom' => $voyageur->getPrenom(),
            'numero_tel' => $voyageur->getNumeroTel(),
            'mail' => $voyageur->getMail(),
            'mdp' => $voyageur->getMdp(),
            'id' => $voyageur->getId()
        ]);
    }

    public function supprimer(int $id): bool {
        $sql = "DELETE FROM voyageur WHERE id = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        return $pdoStatement->execute(['id' => $id]);
    }
}
