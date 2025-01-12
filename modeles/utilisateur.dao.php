<?php
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
                $retour = new Guide(
                    $guide['id'], $guide['nom'], $guide['prenom'], $guide['numero_tel'],
                    $guide['mail'], $guide['mdp'], $guide['chemin_certif'],
                    $guide['statut_compte'], $guide['tentatives_echouees'] // Champs supplémentaires
                );
                return $retour;
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
            // Log l'erreur ou retourne un message détaillé
            error_log("Erreur lors de la recherche par email: " . $e->getMessage());
            return null;
        }
    }

}
?>


    