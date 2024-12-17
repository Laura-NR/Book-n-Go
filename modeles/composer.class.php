<?php
class Composer {
    private ?DateTime $tempsSurPlace;
    private ?int $excursionId;
    private ?int $visiteId;

    //Constructeur
    public function __construct(
        ?DateTime  $tempsSurPlace = null,
        ?int $excursionId = null,
        ?int    $visiteId = null)
        {
            $this->tempsSurPlace = $tempsSurPlace;
            $this->excursionId = $excursionId;
            $this->visiteId = $visiteId;
        }

    /**
     * Get the value of tempsSurPlace
     */ 
    public function getTempsSurPlace()
    {
        return $this->tempsSurPlace;
    }

    /**
     * Set the value of tempsSurPlace
     */ 
    public function setTempsSurPlace(?DateTime $tempsSurPlace): void
    {
        $this->tempsSurPlace = $tempsSurPlace;
    }

    /**
     * Get the value of excursion
     */ 
    public function getExcursion()
    {
        return $this->excursionId;
    }

    /**
     * Set the value of excursion
     */ 
    public function setExcursion(?int $excursionId): void
    {
        $this->excursionId = $excursionId;
    }

    /**
     * Get the value of visite
     */ 
    public function getVisite()
    {
        return $this->visiteId;
    }

    /**
     * Set the value of visite
     */ 
    public function setVisite(?Visite $visiteId): void
    {
        $this->visiteId = $visiteId;
    }
}
