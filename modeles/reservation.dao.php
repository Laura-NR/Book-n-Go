<?php
class ReservationDAO
{
    private $pdo;

    // Constructeur pour établir une connexion à la base de données
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Méthode pour récupérer une réservation par son ID
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

    // Méthode pour insérer une nouvelle réservation
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

    // Méthode pour mettre à jour une réservation
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

    // Méthode pour supprimer une réservation
    public function supprimerReservation($id)
    {
        $sql = "DELETE FROM reservations WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute(); // Retourne true si la suppression réussit
    }

    // Méthode pour récupérer toutes les réservations
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
}
?>
