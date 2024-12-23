<?php
class Composer {
    private ?DateTime $temps_sur_place;
    private ?int $id_excursion;
    private ?int $id_visite;

    //Constructeur
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
     * Get the value of tempsSurPlace
     */ 
    public function getTempsSurPlace()
    {
        return $this->temps_sur_place;
    }

    /**
     * Set the value of tempsSurPlace
     */ 
    public function setTempsSurPlace(?DateTime $tempsSurPlace): void
    {
        $this->temps_sur_place = $tempsSurPlace;
    }

    /**
     * Get the value of excursion
     */ 
    public function getExcursion()
    {
        return $this->id_excursion;
    }

    /**
     * Set the value of excursion
     */ 
    public function setExcursion(?int $excursionId): void
    {
        $this->id_excursion = $excursionId;
    }

    /**
     * Get the value of visite
     */ 
    public function getVisite()
    {
        return $this->id_visite;
    }

    /**
     * Set the value of visite
     */ 
    public function setVisite(?int $visiteId): void
    {
        $this->id_visite = $visiteId;
    }
}
