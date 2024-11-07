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

     public function findAsoc(?int $id): ?array
     {
        $sql="SELECT * FROM ".PREFIXE_TABLE."visite";
        $pdostatement= $this->pdo->prepare($sql);
        $pdostatement->execute();
        $pdostatement->setFetchMode(PDO::FETCH_ASSOC);
        $visite=$pdostatement->fetch();
        return $visite;



     }
     public function findAll(){
        $sql="SELECT * FROM ".PREFIXE_TABLE."visite";
        $pdostatement= $this->pdo->prepare($sql);
        $pdostatement->execute();
        $pdostatement->setFetchMode(PDO::FETCH_ASSOC);
        $categorie=$pdostatement->fetchAll();
        return $categorie;
}

public function hydrate($tableauAssoc): ?Visite
{
 $visite=new Visite();
 $visite->setId($tableauAssoc['id']);
 $visite->setcapacite($tableauAssoc['capacite']);
 $visite->setNom($tableauAssoc['nom']);
 $visite->setCheminImage($tableauAssoc['image']);
 $visite->setDate_visite($tableauAssoc['date_visite']);
 $visite->setDescription($tableauAssoc['description']);
 $visite->setPrive($tableauAssoc['prive']);
 $visite->setId_guide($tableauAssoc['id_guide']);
 return $visite;

}
public function hydrateAll($tableau): ?array
{
 $visite=[];
foreach($tableau as $tableauAssoc){
    $visite=$this->hydrate($tableauAssoc);
    $visite[]=$visite;
}
 return $visite;

}
}