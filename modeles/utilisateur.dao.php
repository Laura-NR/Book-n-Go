<?php
class UtilisateurDao {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // // Recherche d'un utilisateur par email
    // public function findByEmail($email) {
    //     // Vérifier dans la table des guides
    //     $stmt = $this->pdo->prepare("SELECT * FROM guides WHERE email = :email");
    //     $stmt->execute(['email' => $email]);
    //     $guide = $stmt->fetch(PDO::FETCH_ASSOC);

    //     if ($guide) {
    //         return new Utilisateur(
    //             $guide['id'], $guide['nom'], $guide['prenom'], $guide['email'],
    //             $guide['mot_de_passe'], $guide['numero_tel'], 'guide', $guide['certification']
    //         );
    //     }

    //     // Sinon, vérifier dans la table des voyageurs
    //     $stmt = $this->pdo->prepare("SELECT * FROM voyageurs WHERE email = :email");
    //     $stmt->execute(['email' => $email]);
    //     $voyageur = $stmt->fetch(PDO::FETCH_ASSOC);

    //     if ($voyageur) {
    //         return new Utilisateur(
    //             $voyageur['id'], $voyageur['nom'], $voyageur['prenom'], $voyageur['email'],
    //             $voyageur['mot_de_passe'], $voyageur['numero_tel'], 'voyageur'
    //         );
    //     }

    //     return null; // Aucun utilisateur trouvé
    // }

    // Création d'un utilisateur

    // Trouver un utilisateur par email (voyageur ou guide)
    public function findByEmail(string $email) {
        try {
            // Chercher d'abord dans les guides
            $stmt = $this->pdo->prepare("SELECT * FROM guide WHERE mail = :mail");
            $stmt->bindValue(':mail', $email);
            $stmt->execute();
            $guide = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($guide) {
                $retour = new Guide(
                    $guide['id'], $guide['nom'], $guide['prenom'], $guide['numero_tel'],
                    $guide['mail'], $guide['mdp'], $guide['chemin_certif']
                );
                //var_dump($retour);
                return $retour;
                //exit;
            }

            // Chercher dans les voyageurs
            $stmt = $this->pdo->prepare("SELECT * FROM voyageur WHERE mail = :mail");
            $stmt->bindValue(':mail', $email, PDO::PARAM_STR);
            $stmt->execute();
            $voyageur = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($voyageur) {
                return new Voyageur(
                    $voyageur['id'], $voyageur['nom'], $voyageur['prenom'], $voyageur['numero_tel'],
                    $voyageur['mail'], $voyageur['mdp']
                );
            }

            return null;
        } catch (PDOException $e) {
            //echo "Erreur lors de la recherche : " . $e->getMessage();
            return null;
        }
    }
}
?>


    