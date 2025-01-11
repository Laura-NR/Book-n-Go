<?php
class Composer {
    /**
     * @var DateTime|null
     */
    private ?DateTime $temps_sur_place;
    /**
     * @var int|null
     */
    private ?int $id_excursion;
    /**
     * @var int|null
     */
    private ?int $id_visite;

    //Constructeur

    /**
     * @param DateTime|null $temps_sur_place
     * @param int|null $id_excursion
     * @param int|null $id_visite
     */
    public function __construct(
        ?DateTime  $temps_sur_place = null,
        ?int $id_excursion = null,
        ?int    $id_visite = null)
        {
            $this->temps_sur_place = $temps_sur_place;
            $this->id_excursion = $id_excursion;
            $this->id_visite = $id_visite;
        }


    /**
     * @brief Récupère la valeur de temps_sur_place
     * @return DateTime|null
     */
    public function getTempsSurPlace()
    {
        return $this->temps_sur_place;
    }


    /**
     * @brief Set la valeur de temps_sur_place
     * @param DateTime|null $tempsSurPlace
     * @return void
     */
    public function setTempsSurPlace(?DateTime $tempsSurPlace): void
    {
        $this->temps_sur_place = $tempsSurPlace;
    }


    /**
     * @brief Récupère l'id de l'excursion
     * @return int|null
     */
    public function getExcursion()
    {
        return $this->id_excursion;
    }


    /**
     * @brief Affecte l'id de l'excursion
     * @param int|null $excursionId
     * @return void
     */
    public function setExcursion(?int $excursionId): void
    {
        $this->id_excursion = $excursionId;
    }


    /**
     * @brief Récupère l'id de la visite
     * @return int|null
     */
    public function getVisite()
    {
        return $this->id_visite;
    }


    /**
     * @brief Affecte l'id de la visite
     * @param int|null $visiteId
     * @return void
     */
    public function setVisite(?int $visiteId): void
    {
        $this->id_visite = $visiteId;
    }
}
