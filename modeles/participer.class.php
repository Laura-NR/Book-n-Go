<?php 

class Participer {
    private DateTime|null $date_debut;
    private DateTime|null $date_fin;
    private Visite $visite;
    private Guide $guide;
    private Reservation $reservation;

    public function __construct(?DateTime $date_debut = null, ?DateTime $date_fin = null, ?Visite $visite = null, ?Guide $guide = null, ?Reservation $reservation = null) {
        $this->date_debut = $date_debut;
        $this->date_fin = $date_fin;
        $this->visite = $visite;
        $this->guide = $guide;
        $this->reservation = $reservation;
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
     * Get the value of visite
     */ 
    public function getVisite(): ?Visite
    {
        return $this->visite;
    }

    /**
     * Set the value of visite
     *
     */ 
    public function setVisite($visite): void
    {
        $this->visite = $visite;
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