<?php
class Composer {
    private ?DateTime $heureArr;
    private ?DateTime $tempsSurPlace;
    private Excursion $excursion;
    private Visite $visite;

    //Constructeur
    public function __construct(
        ?DateTime  $heureArr = null,
        ?DateTime  $tempsSurPlace = null,
        ?Excursion $excursion = null,
        ?Visite    $visite = null)
        {
            $this->heureArr = $heureArr;
            $this->tempsSurPlace = $tempsSurPlace;
            $this->excursion = $excursion;
            $this->visite = $visite;
        }


    /**
     * Get the value of heureArr
     */ 
    public function getHeureArr()
    {
        return $this->heureArr;
    }

    /**
     * Set the value of heureArr
     */ 
    public function setHeureArr(?DateTime $heureArr): void
    {
        $this->heureArr = $heureArr;
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
        return $this->excursion;
    }

    /**
     * Set the value of excursion
     */ 
    public function setExcursion(?Excursion $excursion): void
    {
        $this->excursion = $excursion;
    }

    /**
     * Get the value of visite
     */ 
    public function getVisite()
    {
        return $this->visite;
    }

    /**
     * Set the value of visite
     */ 
    public function setVisite(?Visite $visite): void
    {
        $this->visite = $visite;
    }
}
