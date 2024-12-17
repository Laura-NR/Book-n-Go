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
    public function creer($utilisateur): bool {
        try {
            if ($utilisateur instanceof Guide) {
                // Si c'est un guide
                $stmt = $this->pdo->prepare("
                    INSERT INTO guides (nom, prenom, numeroTel, mail, mdp, cheminCertification)
                    VALUES (:nom, :prenom, :numeroTel, :mail, :mdp, :cheminCertification)
                ");
                $stmt->bindValue(':cheminCertification', $utilisateur->getCheminCertification(), PDO::PARAM_STR);
            } else {
                // Si c'est un voyageur
                $stmt = $this->pdo->prepare("
                    INSERT INTO voyageurs (nom, prenom, numeroTel, mail, mdp)
                    VALUES (:nom, :prenom, :numeroTel, :mail, :mdp)
                ");
            }

            // Attributs communs
            $stmt->bindValue(':nom', $utilisateur->getNom(), PDO::PARAM_STR);
            $stmt->bindValue(':prenom', $utilisateur->getPrenom(), PDO::PARAM_STR);
            $stmt->bindValue(':numeroTel', $utilisateur->getNumeroTel(), PDO::PARAM_STR);
            $stmt->bindValue(':mail', $utilisateur->getMail(), PDO::PARAM_STR);
            $stmt->bindValue(':mdp', $utilisateur->getMdp(), PDO::PARAM_STR);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de l'insertion : " . $e->getMessage();
            return false;
        }
    }

    // Trouver un utilisateur par email (voyageur ou guide)
    public function findByEmail(string $email) {
        try {
            // Chercher d'abord dans les guides
            $stmt = $this->pdo->prepare("SELECT * FROM guides WHERE mail = :mail");
            $stmt->bindValue(':mail', $email, PDO::PARAM_STR);
            $stmt->execute();
            $guide = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($guide) {
                return new Guide(
                    $guide['id'], $guide['nom'], $guide['prenom'], $guide['numeroTel'], 
                    $guide['mail'], $guide['mdp'], $guide['cheminCertification']
                );
            }

            // Chercher dans les voyageurs
            $stmt = $this->pdo->prepare("SELECT * FROM voyageurs WHERE mail = :mail");
            $stmt->bindValue(':mail', $email, PDO::PARAM_STR);
            $stmt->execute();
            $voyageur = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($voyageur) {
                return new Voyageur(
                    $voyageur['id'], $voyageur['nom'], $voyageur['prenom'], $voyageur['numeroTel'], 
                    $voyageur['mail'], $voyageur['mdp']
                );
            }

            return null;
        } catch (PDOException $e) {
            echo "Erreur lors de la recherche : " . $e->getMessage();
            return null;
        }
    }
}
?>


    