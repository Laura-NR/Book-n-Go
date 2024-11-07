<?php

class CommentaireDao{
    private ?PDO $pdo;

    public function __construct(?PDO $pdo = null) {
        $this->pdo = $pdo;
    }

    public function getPdo(): ?PDO {
        return $this->pdo;
    }

    public function setPdo(): void {
        $this->pdo = $pdo;
    }

    public function find(?int $id): ?Commentaire {
        $sql = "SELECT * FROM commentaire WHERE id= :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array("id"=>$id));
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Commentaire");
        $commentaire = $pdoStatement->fetch();
        return $commentaire;
    }

    public function findAll(){
        $sql = "SELECT * FROM commentaire";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Commentaire");
        $commentaire = $pdoStatement->fetchAll();
        return $commentaire;
    }

    public function findAssoc(?int $id): ?array {
        $sql = "SELECT * FROM commentaire WHERE id= :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array("id"=>$id));
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $commentaire = $pdoStatement->fetch();
        return $commentaire;
    }

    public function findAllAssoc(){
        $sql = "SELECT * FROM commentaire";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $commentaire = $pdoStatement->fetchAll();
        return $commentaire;
    }


    public function hydrate($tableauAssoc): ?Commentaire {
        $commentaire = new Commentaire();
        $commentaire->setId($tableauAssoc['id']);
        $commentaire->setDateHeurePublication($tableauAssoc['date_heure_publication']);
        $commentaire->setContenu($tableauAssoc['contenu']);
        $commentaire->setIdVoyageur($tableauAssoc['id_voyageur']);
        $commentaire->setIdPost($tableauAssoc['id_post']);
    }

    public function hydrateAll($tableau): ?array {
        $commentaires = [];
        foreach($tableau as $tableauAssoc){
            $commentaire = $this->hydrate($tableau);
            $commentaires[] = $commentaire;
        }
        return $commentaires;
    }


}