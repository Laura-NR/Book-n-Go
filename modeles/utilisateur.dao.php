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


    