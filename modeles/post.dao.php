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
        $sql = "SELECT * FROM post WHERE id = :id";
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
        $post->setVoyageur($tableauAssoc['id_voyageur']);
        $post->setpoint($tableauAssoc['id_point']);
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
}