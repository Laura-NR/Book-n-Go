<?php
class VisiteDao{
    private ?PDO $pdo;

     function __construct(PDO $pdo = null) {
        $this->pdo = $pdo;
    }

     public function getPdo(): ?PDO
     {
          return $this->pdo;
     }

     public function setPdo($pdo):void
     {
          $this->pdo = $pdo;

     }
     //  obtenir les informations concernant une visites en particulier
     public function find(?int $id): ?Visite
     {
         $sql = "SELECT * FROM visite WHERE id = :id";
         $pdoStatement = $this->pdo->prepare($sql);
         $pdoStatement->execute(array(':id' => $id));
         $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
         $visite = $pdoStatement->fetch();
         return $visite;
     }
    //  obtenir toutes les informations de la table `visite`
     public function findAll(): ?array
     {
        $sql="SELECT * FROM visite";
        $pdostatement= $this->pdo->prepare($sql);// Préparation de la requête SQL
        $pdostatement->execute();// Exécution de la requête
        //Définir le mode de récupération des résultats comme un tableau associatif
        $pdostatement->setFetchMode(PDO::FETCH_ASSOC);
        //Récupération de toutes les lignes de résultats sous forme de tableau
        $visite=$pdostatement->fetchAll();
        return $visite;
     }


//permet de remplir la nvl instance de Visite avec les données recup dans la bd 
public function hydrate($tableauAssoc): ?Visite
{
 $visite=new Visite();
 $visite->setId($tableauAssoc['id']);
 $visite->setcapacite($tableauAssoc['capacite']);
 $visite->setNom($tableauAssoc['nom']);
 $visite->setCheminImage($tableauAssoc['image']);
 $visite->setDate_visite($tableauAssoc['date_visite']);
 $visite->setDescription($tableauAssoc['description']);
 $visite->setPublic($tableauAssoc['prive']);
 $visite->setId_guide($tableauAssoc['id_guide']);
 return $visite;

}
public function hydrateAll(array $tableauAssoc): ?array
{
    $visites = [];
    foreach ($tableauAssoc as $ligne) {
        $visite = new Visite();
        $visite = $this->hydrate($ligne);
        $visites[] = $visite;
    }
    return $visites;
}
}