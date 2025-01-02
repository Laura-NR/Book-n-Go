<?php

class PostDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo=null)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM post";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $posts = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        return $posts;
    }

    public function findAllByCarnetId(int $carnetId): array
    {
        $sql = "SELECT P.*, V.titre AS titre_visite FROM post P JOIN visite V ON V.id = P.id_visite WHERE id_carnet = :carnetId";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array(':carnetId' => $carnetId));
        $posts = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        return $posts;
    }

    /**
     * Get the value of pdo
     */ 
    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }

    /**
     * Set the value of pdo
     */ 
    public function setPdo(?PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    public function findAssoc(?int $id): ?Post
    {
        $sql = "SELECT * FROM post WHERE id = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array(':id' => $id));
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $post = $pdoStatement->fetch();
        return $post;
    }

    public function find(?int $id): ?Post
    {
        $sql = "SELECT P.*, V.titre AS titre_visite FROM post P JOIN visite V ON V.id = P.id_visite WHERE P.id = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array(':id' => $id));
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Post');
        $post = $pdoStatement->fetch();
        return $post;
    }

    public function hydrate(array $tableauAssoc): ?Post
    {
        $post = new Post();
        $post->setId($tableauAssoc['id']);
        $post->setTitre($tableauAssoc['titre']);
        $post->setChemin_img($tableauAssoc['chemin_img']);
        $post->setContenu($tableauAssoc['contenu']);
        $post->setDateHeurePublication($tableauAssoc['date_heure_publication']);
        $post->setVoyageur($tableauAssoc['id_voyageur']);
        $post->setVisite($tableauAssoc['id_point']);
        return $post;
    }

    public function hydrateAll(array $tableauAssoc): ?array
    {
        $posts = [];
        foreach ($tableauAssoc as $ligne) {
            $post = new Post();
            $post = $this->hydrate($ligne);
            $posts[] = $post;
        }
        return $posts;
    }

    public function inserer(Post $post): bool
    {
        $datePublication = new DateTime();
        $datePublicationString = $datePublication->format('Y-m-d');

        var_dump($post);
        $sql = "INSERT INTO post (chemin_img, titre, contenu, date_publication, id_carnet, id_visite) VALUES (:chemin_img, :titre, :contenu, :date_publication, :id_carnet, :id_visite)";
        $pdoStatement = $this->pdo->prepare($sql);
        $resultat = $pdoStatement->execute(array(
            "chemin_img" => $post->getChemin_img(),
            "titre" => $post->getTitre(),
            "contenu" => $post->getContenu(),
            "date_publication" => $datePublicationString,
            "id_carnet" => $post->getIdCarnet(),
            "id_visite" => $post->getIdVisite()
        ));
        return $resultat;
    }

    public function retirer(mixed $idPost)
    {
        $sql = "DELETE FROM post WHERE id = :idPost";
        $pdoStatement = $this->pdo->prepare($sql);
        return $pdoStatement->execute([':idPost' => $idPost]);
    }
}