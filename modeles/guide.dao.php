<?php
class GuideDao{
    private ?PDO $pdo;

    public function __construct(?PDO $pdo=null){
        $this->pdo = $pdo;
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
     *
     */ 
    public function setPdo($pdo): void
    {
        $this->pdo = $pdo;
    }

    
    public function find(?int $id): ?Guide
    {
        $sql="SELECT * FROM guide WHERE id= :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array("id"=>$id));
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Guide');
        $guide = $pdoStatement->fetch();
        return $guide;
    }

    public function findAll(){
        $sql="SELECT * FROM  guide";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Guide');
        $guide = $pdoStatement->fetchAll();
        return $guide;
    }

    public function findAssoc(?int $id): ?array
    {
        $sql="SELECT * FROM guide WHERE id= :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array("id"=>$id));
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $guide = $pdoStatement->fetch();
        return $guide;
    }

    public function findAllAssoc(){
        $sql="SELECT * FROM guide";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $guide = $pdoStatement->fetchAll();
        return $guide;
    }

    public function hydrate($tableauAssoc): ?Guide
    {
        $guide = new Guide();
        $guide->setId($tableauAssoc['id']);
        $guide->setNom($tableauAssoc['nom']);
        $guide->setPrenom($tableauAssoc['prenom']);
        $guide->setNumeroTel($tableauAssoc['mail']);
        $guide->setMail($tableauAssoc['numero_tel']);
        $guide->setMdp($tableauAssoc['mdp']);
        $guide->setCheminCertification($tableauAssoc['chemin_certif']);
        return $guide;
    }

    public function hydrateAll($tableau): ?array{
        $guides = [];
        foreach($tableau as $tableauAssoc){
            $guide = $this->hydrate($tableauAssoc);
            $guides[] = $guide;
        }
        return $guides;
    }

    // creer 

    // maj

    //supp

    //lister 

    //afficher 
}