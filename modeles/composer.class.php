<?php
class Composer {
    private int|null $id;
    private ?DateTime $heureArr;
    private ?DateTime $tempsSurPlace;
    private Visite $visite;
    private PointItineraire $point;

    //Constructeur
    public function __construct(
        ?int $id = null,
        ?DateTime $heureArr = null,
        ?DateTime $tempsSurPlace = null,
        ?Visite $visite = null,
        ?PointItineraire $point = null)
        {
            $this->id = $id;
            $this->heureArr = $heurreArr;
            $this->tempsSurPlace = $tempsSurPlace;
            $this->visite = $visite;
            $this->point = $point;
        }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */ 
    public function setId(?int $id): void
    {
        $this->id = $id;
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

    /**
     * Get the value of point
     */ 
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * Set the value of point
     */ 
    public function setPoint(?PointItineraire $point): void
    {
        $this->point = $point;
    }
}
