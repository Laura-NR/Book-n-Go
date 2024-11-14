<?php 

class Engagement {
    private int|null $id;
    private DateTime|null $date_debut;
    private DateTime|null $date_fin;
    private Excursion $excursion;
    private Guide $guide;

    public function __construct(?int $id = null, ?DateTime $date_debut = null, ?DateTime $date_fin = null, ?Excursion $excursion = null, ?Guide $guide = null) {
        $this->id = $id;
        $this->date_debut = $date_debut;
        $this->date_fin = $date_fin;
        $this->excursion = $excursion;
        $this->guide = $guide;
    }

    /**
     * Get the value of the id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the value of the id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * Get the value of date_debut
     */ 
    public function getDate_debut(): ?DateTime
    {
        return $this->date_debut;
    }

    /**
     * Set the value of date_debut
     *
     */ 
    public function setDate_debut($date_debut): void
    {
        $this->date_debut = $date_debut;
    }

    /**
     * Get the value of date_fin
     */ 
    public function getDate_fin(): ?DateTime
    {
        return $this->date_fin;
    }

    /**
     * Set the value of date_fin
     *
     */ 
    public function setDate_fin($date_fin): void
    {
        $this->date_fin = $date_fin;
    }

    /**
     * Get the value of excursion
     */ 
    public function getExcursion(): ?Excursion
    {
        return $this->excursion;
    }

    /**
     * Set the value of excursion
     *
     */ 
    public function setExcursion($excursion): void
    {
        $this->excursion = $excursion;
    }

    /**
     * Get the value of guide
     */ 
    public function getGuide(): ?Guide
    {
        return $this->guide;
    }

    /**
     * Set the value of guide
     *
     */ 
    public function setGuide($guide): void
    {
        $this->guide = $guide;
    }
}