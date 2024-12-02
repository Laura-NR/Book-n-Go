<?php

class CommentaireDao{
    private ?PDO $pdo;

    public function __construct(?PDO $pdo = null) {
        $this->pdo = bd::getInstance()->getPdo();
    }

    public function getPdo(): ?PDO {
        return $this->pdo;
    }

    public function setPdo(?PDO $pdo): void {
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
        return $commentaire;
    }

    public function hydrateAll($tableau): ?array {
        $commentaires = [];
        foreach($tableau as $tableauAssoc){
            $commentaire = $this->hydrate($tableau);
            $commentaires[] = $commentaire;
        }
        return $commentaires;
    }

    public function findAllWithIdPost($idPost)
    {
        $sql = "SELECT C.*, V.nom, V.prenom FROM commentaire C JOIN post P ON P.id = C.id_post JOIN voyageur V on V.id = C.id_voyageur WHERE id_post = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array("id"=>$idPost));
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $commentaire = $pdoStatement->fetchAll();
        return $commentaire;
    }

    public function inserer(Commentaire $commentaire)
    {
        $sql = "INSERT INTO commentaire (date_heure_publication, contenu, id_voyageur, id_post) VALUES (:date_heure_publication, :contenu, :id_voyageur, :id_post)";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array(
            "date_heure_publication" => $commentaire->getDateHeurePublication(),
            "contenu" => $commentaire->getContenu(),
            "id_voyageur" => $commentaire->getIdVoyageur(),
            "id_post" => $commentaire->getIdPost()
        ));
    }


}