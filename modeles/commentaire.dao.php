<?php
/**
 * @class CommentaireDao
 * @brief Classe DAO pour la gestion des commentaires dans la base de données.
 *
 * Elle permet de créer, lire, mettre à jour et supprimer des commentaires.
 * Elle fournit également des méthodes pour récupérer un commentaire spécifique ou tous les commentaires.
 */
class CommentaireDao{
    private ?PDO $pdo;

    /**
     * @param PDO|null $pdo
     */
    public function __construct(?PDO $pdo = null) {
        $this->pdo = bd::getInstance()->getPdo();
    }

    /**
     * @return PDO|null
     */
    public function getPdo(): ?PDO {
        return $this->pdo;
    }

    /**
     * @param PDO|null $pdo
     * @return void
     */
    public function setPdo(?PDO $pdo): void {
        $this->pdo = $pdo;
    }

    /**
     * @brief Rechercher un commentaire par son id
     * @param int|null $id
     * @return Commentaire|null -> objet Commentaire
     */
    public function find(?int $id): ?Commentaire {
        $sql = "SELECT * FROM commentaire WHERE id= :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array("id"=>$id));
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Commentaire");
        $commentaire = $pdoStatement->fetch();
        return $commentaire;
    }

    /**
     * @brief Rechercher tous les commentaires
     * @return array|false -> tableau associatif d'objets Commentaire
     */
    public function findAll(){
        $sql = "SELECT * FROM commentaire";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Commentaire");
        $commentaire = $pdoStatement->fetchAll();
        return $commentaire;
    }

    /**
     * @brief Rechercher un commentaire par son id
     * @param int|null $id
     * @return array|null -> tableau associatif du commentaire
     */
    public function findAssoc(?int $id): ?array {
        $sql = "SELECT * FROM commentaire WHERE id= :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array("id"=>$id));
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $commentaire = $pdoStatement->fetch();
        return $commentaire;
    }

    /**
     * @brief Rechercher tous les commentaires
     * @return array|false -> tableau associatif de tous les commentaires
     */
    public function findAllAssoc(){
        $sql = "SELECT * FROM commentaire";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $commentaire = $pdoStatement->fetchAll();
        return $commentaire;
    }


    /**
     * @brief Hydrate un commentaire
     * @param $tableauAssoc
     * @return Commentaire|null -> objet Commentaire
     */
    public function hydrate($tableauAssoc): ?Commentaire {
        $commentaire = new Commentaire();
        $commentaire->setId($tableauAssoc['id']);
        $commentaire->setDateHeurePublication($tableauAssoc['date_heure_publication']);
        $commentaire->setContenu($tableauAssoc['contenu']);
        $commentaire->setIdVoyageur($tableauAssoc['id_voyageur']);
        $commentaire->setIdPost($tableauAssoc['id_post']);
        return $commentaire;
    }

    /**
     * @brief Hydrate tous les commentaires
     * @param $tableau
     * @return array|null -> tableau d'objets Commentaire
     */
    public function hydrateAll($tableau): ?array {
        $commentaires = [];
        foreach($tableau as $tableauAssoc){
            $commentaire = $this->hydrate($tableau);
            $commentaires[] = $commentaire;
        }
        return $commentaires;
    }

    /**
     * @brief Rechercher tous les commentaires d'un post
     * @param $idPost
     * @return array|false -> tableau associatif de tous les commentaires
     * @throws DateMalformedStringException
     */
    public function findAllWithIdPost($idPost)
    {
        $sql = "SELECT C.*, V.nom, V.prenom FROM commentaire C JOIN post P ON P.id = C.id_post JOIN voyageur V on V.id = C.id_voyageur WHERE id_post = :id ORDER BY C.date_heure_publication DESC";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array("id"=>$idPost));
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $commentaires = $pdoStatement->fetchAll();
//         Convertir la colonne date_heure_publication en objet DateTime
        foreach ($commentaires as $cle => $commentaire) {
            $dateHeurePublication = new DateTime($commentaire['date_heure_publication']);
            $commentaires[$cle]['date_heure_publication'] = $dateHeurePublication->format('Y-m-d H:i:s');
        }
        return $commentaires;
    }

    /**
     * @brief Inserer un commentaire
     * @param Commentaire $commentaire
     * @return void
     */
    public function inserer(Commentaire $commentaire)
    {
        $sql = "INSERT INTO commentaire (date_heure_publication, contenu, id_voyageur, id_post) VALUES (:date_heure_publication, :contenu, :id_voyageur, :id_post)";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array(
            "date_heure_publication" => $commentaire->getDateHeurePublication()->format('Y-m-d H:i:s'),
            "contenu" => $commentaire->getContenu(),
            //ID DE VOYAGEUR TEMPORAIRE POUR TEST IL SERA PLUS TARD RECUPERE DE SESSION
            "id_voyageur" => $commentaire->getIdVoyageur(),
            "id_post" => $commentaire->getIdPost()
        ));
    }

    /**
     * @brief Retirer un commentaire par son id
     * @param $idCommentaire -> id du commentaire à retirer
     * @return void
     */
    public function retirer($idCommentaire)
    {
        $sql = "DELETE FROM commentaire WHERE id = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array(
            //ID DE VOYAGEUR TEMPORAIRE POUR TEST IL SERA PLUS TARD RECUPERE DE SESSION
            "id" => $idCommentaire,
        ));
    }


}