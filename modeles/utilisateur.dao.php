<?php
/**
 * @file utilisateur.dao.php
 * @class UtilisateurDao
 * @brief Classe d'accès aux données (DAO) pour les utilisateurs.
 *
 * Elle permet de créer, lire, mettre à jour et supprimer des utilisateurs.
 * Elle fournit également des méthodes pour récupérer un utilisateur spécifique
 * ou tous les utilisateurs.
 */
class UtilisateurDao {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * @brief Rechercher un utilisateur par son email. l'utilisateur retourné peut être un guide comme un voyageur
     * @param string $email
     * @return Guide|Voyageur|null
     */
    public function findByEmail(string $email) {
        try {
            // Nettoyage et validation de l'email
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return null;  // Retourne null si l'email est invalide
            }

            // Chercher d'abord dans les guides
            $stmt = $this->pdo->prepare("SELECT * FROM guide WHERE mail = :mail");
            $stmt->bindValue(':mail', $email);
            $stmt->execute();
            $guide = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($guide) {
                // Crée un objet Guide avec de nouveaux champs si nécessaire
                $dateDernierEchec = $guide['date_dernier_echec'] ? new DateTime($guide['date_dernier_echec']) : null;
                $derniereCo = $guide['derniere_co'] ? new DateTime($guide['derniere_co']) : null;

                $retour = new Guide(
                    $guide['id'], $guide['nom'], $guide['prenom'], $guide['numero_tel'],
                    $guide['mail'], $guide['mdp'], $derniereCo, $guide['tentatives_echouees'],
                    $dateDernierEchec, $guide['statut_compte'], $guide['chemin_certif']  // Champs supplémentaires
                );
                return $retour;
            }

            // Chercher dans les voyageurs
            $stmt = $this->pdo->prepare("SELECT * FROM voyageur WHERE mail = :mail");
            $stmt->bindValue(':mail', $email, PDO::PARAM_STR);
            $stmt->execute();
            $voyageur = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($voyageur) {
                $dateDernierEchec = $voyageur['date_dernier_echec'] ? new DateTime($voyageur['date_dernier_echec']) : null;
                $derniereCo = $voyageur['derniere_co'] ? new DateTime($voyageur['derniere_co']) : null;
                return new Voyageur(
                    $voyageur['id'], $voyageur['nom'], $voyageur['prenom'], $voyageur['numero_tel'],
                    $voyageur['mail'], $voyageur['mdp'], $derniereCo, $voyageur['tentatives_echouees'],
                    $dateDernierEchec, $voyageur['statut_compte']
                );
            }

            return null;
        } catch (PDOException $e) {
            // Log l'erreur ou retourne un message détaillé
            error_log("Erreur lors de la recherche par email: " . $e->getMessage());
            return null;
        }
    }

    public function majStatutCompte(Voyageur|Guide $utilisateur)
    {
        try {
            if ($utilisateur instanceof Guide) {
                $table = 'guide';
            } else if ($utilisateur instanceof Voyageur) {
                $table = 'voyageur';
            } else {
                return false; // Type d'utilisateur non géré
            }

            echo "var dump depuis le dao";
            //var_dump($utilisateur);
            //var_dump($utilisateur->getTentativesEchouees());

            $stmt = $this->pdo->prepare("UPDATE $table SET 
                tentatives_echouees = :tentatives_echouees,
                date_dernier_echec = :date_dernier_echec,
                statut_compte = :statut_compte
                WHERE id = :id");

            $stmt->bindValue(':tentatives_echouees', $utilisateur->getTentativesEchouees(), PDO::PARAM_INT);
            $dateDernierEchec = $utilisateur->getDateDernierEchec();
            $stmt->bindValue(':date_dernier_echec', $dateDernierEchec ? $dateDernierEchec->format('Y-m-d H:i:s') : null);
            $stmt->bindValue(':statut_compte', $utilisateur->getStatutCompte(), PDO::PARAM_STR);
            $stmt->bindValue(':id', $utilisateur->getId(), PDO::PARAM_INT);

            //var_dump($stmt->execute());

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour du statut du compte: " . $e->getMessage());
            return false;
        }
    }
}
?>


    