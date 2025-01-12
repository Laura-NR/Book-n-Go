<?php
/**
 * @class ReservationDAO
 * @brief Classe DAO pour la gestion des réservations dans la base de données.
 *
 * Elle permet de créer, lire, mettre à jour et supprimer des réservations.
 * Elle fournit également des méthodes pour récupérer une réservation spécifique par son ID.
 *
 */
class ReservationDAO
{
    private $pdo;

    /**
     * @param $pdo
     */
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @brief Recupère une reservation par son id
     * @param $id
     * @return Reservation|null
     */
    public function getReservationById($id)
    {
        $sql = "SELECT * FROM reservations WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            return new Reservation($row['id'], $row['id_voyageur'], $row['date_reservation'], $row['id_engagement']);
        } else {
            return null; // Aucune réservation trouvée
        }
    }

    /**
     * @brief Inserer une reservation
     * @param Reservation $reservation
     * @return mixed
     */
    public function insererReservation(Reservation $reservation)
    {
        $sql = "INSERT INTO reservations (id_voyageur, date_reservation, id_engagement) 
                VALUES (:id_voyageur, :date_reservation, :id_engagement)";
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindParam(':id_voyageur', $reservation->getIdVoyageur(), PDO::PARAM_INT);
        $stmt->bindParam(':date_reservation', $reservation->getDateReservation(), PDO::PARAM_STR);
        $stmt->bindParam(':id_engagement', $reservation->getIdEngagement(), PDO::PARAM_INT);
        
        return $stmt->execute(); // Retourne true si l'insertion réussit
    }

    /**
     * @brief Mettre à jour une reservation
     * @param Reservation $reservation
     * @return mixed
     */
    public function majReservation(Reservation $reservation)
    {
        $sql = "UPDATE reservations 
                SET id_voyageur = :id_voyageur, date_reservation = :date_reservation, id_engagement = :id_engagement 
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindParam(':id', $reservation->getId(), PDO::PARAM_INT);
        $stmt->bindParam(':id_voyageur', $reservation->getIdVoyageur(), PDO::PARAM_INT);
        $stmt->bindParam(':date_reservation', $reservation->getDateReservation(), PDO::PARAM_STR);
        $stmt->bindParam(':id_engagement', $reservation->getIdEngagement(), PDO::PARAM_INT);
        
        return $stmt->execute(); // Retourne true si la mise à jour réussit
    }

    /**
     * @brief Supprimer une reservation
     * @param $id
     * @return mixed
     */
    public function supprimerReservation($id)
    {
        $sql = "DELETE FROM reservations WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute(); // Retourne true si la suppression réussit
    }

    /**
     * @brief Recupérer toutes les reservations
     * @return array
     */
    public function getAllReservations()
    {
        $sql = "SELECT * FROM reservations";
        $stmt = $this->pdo->query($sql);
        
        $reservations = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $reservations[] = new Reservation($row['id'], $row['id_voyageur'], $row['date_reservation'], $row['id_engagement']);
        }
        
        return $reservations; // Retourne un tableau d'objets Reservation
    }

    /**
     * @brief Inserer une reservation
     * @param Reservation $reservation
     * @return mixed
     */
    public function inserer(Reservation $reservation)
    {
        $sql = "INSERT INTO reservation (id_voyageur, date_reservation, id_engagement) 
                VALUES (:id_voyageur, :date_reservation, :id_engagement)";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':id_voyageur', $reservation->getIdVoyageur(), PDO::PARAM_INT);
        $stmt->bindValue(':date_reservation', $reservation->getDateReservation()->format('Y-m-d'), PDO::PARAM_STR);
        $stmt->bindValue(':id_engagement', $reservation->getIdEngagement(), PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * @brief Rechercher toutes les reservations d'une excursion à partir de son id
     * @param int $excursionId
     * @return array
     * @throws DateMalformedStringException
     */
    public function findReservationsByExcursionId(int $excursionId): array
    {
        $sql = "SELECT r.* 
            FROM reservation r
            JOIN engagement e ON r.id_engagement = e.id
            WHERE e.id_excursion = :id_excursion";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id_excursion', $excursionId, PDO::PARAM_INT);
        $stmt->execute();

        $reservations = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $reservations[] = new Reservation(
                $row['id'],
                $row['id_voyageur'],
                new DateTime($row['date_reservation']),
                $row['id_engagement']
            );
        }
        return $reservations;
    }

    /**
     * @brief Rechercher toutes les reservations d'un voyageur à partir de son id
     * @param $id_voyageur
     * @return mixed
     */
    function getReservationsByVoyageur($id_voyageur)
    {
        $sql = "
    SELECT
        r.date_reservation,
        e.nom AS titre_excursion,
        e.description AS description_excursion,
        e.chemin_image,
        g.nom AS nom_guide,
        g.prenom AS prenom_guide,
        eng.date_debut_dispo,
        eng.date_fin_dispo
    FROM
        reservation r
    JOIN
        engagement eng ON r.id_engagement = eng.id
    JOIN
        excursion e ON eng.id_excursion = e.id
    JOIN
        guide g ON eng.id_guide = g.id
    WHERE
        r.id_voyageur = :id_voyageur;
    ";

        // Préparer la requête
        $stmt = $this->pdo->prepare($sql);

        // Lier le paramètre :id_voyageur
        $stmt->bindParam(':id_voyageur', $id_voyageur, PDO::PARAM_INT);

        // Exécuter la requête
        $stmt->execute();

        // Retourner les résultats
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



}
?>
