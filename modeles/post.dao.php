<?php

class PostDAO
{
    private PDO $pdo;

    /**
     * @param PDO|null $pdo
     */
    public function __construct(PDO $pdo=null)
    {
        $this->pdo = $pdo;
    }

    /**
     * @brief Trouver tous les posts (retourne un tableau associatif de tous les posts)
     * @return array
     */
    public function findAllAssoc(): array
    {
        $sql = "SELECT * FROM post";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $posts = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        return $posts;
    }

    /**
     * @brief Trouver tous les posts d'un carnet dont l'id est passé en paramètre (retourne un tableau associatif de tous les posts)
     * @param int $carnetId
     * @return array
     */
    public function findAllByCarnetId(int $carnetId): array
    {
        $sql = "SELECT P.*, V.titre AS titre_visite FROM post P JOIN visite V ON V.id = P.id_visite WHERE id_carnet = :carnetId";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array(':carnetId' => $carnetId));
        $posts = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        return $posts;
    }


    /**
     * @return PDO|null
     */
    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }


    /**
     * @param PDO|null $pdo
     * @return void
     */
    public function setPdo(?PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    /**
     * @brief Trouver un post par ID en mode associatif (retourne un tableau associatif ou null)
     * @param int|null $id
     * @return Post|null
     */
    public function findAssoc(?int $id): ?Post
    {
        $sql = "SELECT * FROM post WHERE id = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array(':id' => $id));
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $post = $pdoStatement->fetch();
        return $post;
    }

    /**
     * @brief Trouver un post par ID
     * @param int|null $id
     * @return Post|null
     */
    public function find(?int $id): ?Post
    {
        $sql = "SELECT P.*, V.titre AS titre_visite FROM post P JOIN visite V ON V.id = P.id_visite WHERE P.id = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array(':id' => $id));
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Post');
        $post = $pdoStatement->fetch();
        return $post;
    }

    /**
     * @brief Hydrater un post à partir d'un tableau associatif
     * @param array $tableauAssoc
     * @return Post|null
     */
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

    /**
     * @brief Hydrater un tableau de posts à partir de tableaux associatifs
     * @param array $tableauAssoc
     * @return array|null
     */
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

    /**
     * @brief Inserer un post
     * @param Post $post
     * @return bool
     */
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

    /**
     * @brief Supprimer un post de la bd
     * @param mixed $idPost
     * @return bool
     */
    public function retirer(mixed $idPost)
    {
        $sql = "DELETE FROM post WHERE id = :idPost";
        $pdoStatement = $this->pdo->prepare($sql);
        return $pdoStatement->execute([':idPost' => $idPost]);
    }
}