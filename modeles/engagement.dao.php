<?php
/**
 * @file engagement.dao.php
 * @brief Classe d'accès aux données pour les Engagements
 * @class EngagementDao
 * @brief Classe d'accès aux données pour les Engagements
 * Cette classe fournit des méthodes pour accéder et modifier les Engagements dans la base de données.
 * Elle permet de créer, lire, mettre à jour et supprimer des engagements.
 */
class EngagementDao {
    /**
     * @var PDO
     */
    private PDO $pdo;

    /**
     * @param PDO|null $pdo
     */
    public function __construct(PDO $pdo=null)
    {
        $this->pdo = bd::getInstance()->getPdo();
    }

    /**
     * @brief Recherche tous les Engagements dans la base de données
     * @return array
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM engagement";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute();
        $engagements = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        return $engagements;
    }

    /**
     * @brief Retourne le PDO
     * @return PDO|null
     */
    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }


    /**
     * @brief Affecte le PDO
     * @param PDO|null $pdo
     * @return void
     */
    public function setPdo(?PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    /**
     * @brief Rechercher un engagement par son id
     * @param int|null $id
     * @return array|null -> tableau associatif contenant les informations de l'engagement
     */
    public function findAssoc(?int $id): ?array
    {
        $sql = "SELECT * FROM engagement WHERE id = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array(':id' => $id));
        $pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $engagement = $pdoStatement->fetch();
        return $engagement;
    }

    /**
     * @brief Rechercher un engagement par son id
     * @param int|null $id
     * @return Engagement|null -> objet Engagement
     */
    public function find(?int $id): ?Engagement
    {
        $sql = "SELECT * FROM engagement WHERE id = :id";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute(array(':id' => $id));
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Engagement');
        $engagement = $pdoStatement->fetch();
        return $engagement;
    }

    /**
     * @brief Hydrate un engagement
     * @param array $tableauAssoc
     * @return Engagement|null -> objet Engagement
     * @throws DateMalformedStringException
     */
    public function hydrate(array $tableauAssoc): ?Engagement // DATES A CORRIGER
    {
        $engagement = new Engagement();
        $engagement->setId($tableauAssoc['id']);
        $engagement->setDateDebutDispo(new DateTime($tableauAssoc['date_debut_dispo']));
        $engagement->setDateFinDispo(new DateTime($tableauAssoc['date_fin_dispo']));
        $engagement->setExcursion($tableauAssoc['id_excursion']);
        $engagement->setGuide($tableauAssoc['id_guide']);
        $engagement->setHeureDebut(new DateTime($tableauAssoc['heure_debut']));
        return $engagement;
    }

    /**
     * @brief Hydrate un tableau associatif en des engagements
     * @param array $tableauAssoc
     * @return array|null
     * @throws DateMalformedStringException
     */
    public function hydrateAll(array $tableauAssoc): ?array
    {
        $engagements = [];
        foreach ($tableauAssoc as $ligne) {
            $engagement = new Engagement();
            $engagement = $this->hydrate($ligne);
            $engagements[] = $engagement;
        }
        return $engagements;
    }

    /**
     * @brief Créer un engagement en base de données
     * @param Engagement $engagement
     * @return bool
     */
    public function creer(Engagement $engagement): bool
    {
        $sql = "INSERT INTO engagement (date_debut_dispo, date_fin_dispo, id_excursion, id_guide, heure_debut) VALUES (:date_debut_dispo, :date_fin_dispo, :id_excursion, :id_guide, :heure_debut)";
        $pdoStatement = $this->pdo->prepare($sql);
        return $pdoStatement->execute(array(
            ':date_debut_dispo' => $engagement->getDateDebutDispo()->format('Y-m-d'),
            ':date_fin_dispo' => $engagement->getDateFinDispo()->format('Y-m-d'),
            ':id_excursion' => $engagement->getExcursion(),
            ':id_guide' => $engagement->getIdGuide(),
            ':heure_debut' => $engagement->getHeureDebut()->format('Y-m-d H:i:s'),
        ));
    }

    /**
     * @brief Rechercher tous les engagements d'une excursion par le biais de l'id de l'excursion correspondante
     * @param int $excursionId
     * @return array
     * @throws DateMalformedStringException
     */
    public function findEngagementsByExcursionId(int $excursionId): array
    {
        $sql = "SELECT * FROM engagement WHERE id_excursion = :id_excursion";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id_excursion', $excursionId, PDO::PARAM_INT);
        $stmt->execute();

        $engagements = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $engagements[] = new Engagement(
                $row['id'],
                new DateTime($row['date_debut_dispo']),
                new DateTime($row['date_fin_dispo']),
                $row['id_excursion'],
                $row['id_guide'],
                new DateTime($row['heure_debut'])
            );
        }
        return $engagements;
    }

    /**
 * @brief Vérifier si le guide a créé un engagement pour une excursion à une date et une heure données
 *
 * @param int $guideId L'identifiant du guide.
 * @param DateTime $dateDebut La date de début de l'engagement.
 * @param DateTime $dateFin La date de fin de l'engagement.
 * @return bool Retourne vrai si le guide a déjà un engagement pour l'excursion à la date et l'heure données, faux sinon.
 */
public function conflitsEngagements(int $guideId, DateTime $dateDebut, DateTime $dateFin): bool
{
    $sql = "SELECT COUNT(*) FROM engagement 
            WHERE id_guide = :id_guide 
            AND (
                (date_debut_dispo <= :date_fin AND date_fin_dispo >= :date_debut))";
    
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        ':id_guide' => $guideId,
        ':date_debut' => $dateDebut->format('Y-m-d'),
        ':date_fin' => $dateFin->format('Y-m-d')
    ]);

    return $stmt->fetchColumn() > 0;
}
}