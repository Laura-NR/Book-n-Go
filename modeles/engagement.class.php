<?php 

class Engagement {
    private int|null $id;
    private DateTime|null $date_debut_dispo;
    private DateTime|null $date_fin_dispo;
    private int|null $id_excursion;
    private int|null $id_guide;
    private DateTime|null $heure_debut;

    public function __construct(?int $id = null, ?DateTime $date_debut_dispo = null, ?DateTime $date_fin_dispo = null, ?int $id_excursion = null, ?int $id_guide = null, ?DateTime $heure_debut = null) {
        $this->id = $id;
        $this->date_debut_dispo = $date_debut_dispo;
        $this->date_fin_dispo = $date_fin_dispo;
        $this->id_excursion = $id_excursion;
        $this->id_guide = $id_guide;
        $this->heure_debut = $heure_debut;
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
        return $this->date_debut_dispo;
    }

    /**
     * Set the value of date_debut
     *
     */ 
    public function setDate_debut(?DateTime $date_debut_dispo): void
    {
        $this->date_debut_dispo = $date_debut_dispo;
    }

    /**
     * Get the value of date_fin
     */ 
    public function getDate_fin(): ?DateTime
    {
        return $this->date_fin_dispo;
    }

    /**
     * Set the value of date_fin
     *
     */ 
    public function setDate_fin(?DateTime $date_fin_dispo): void
    {
        $this->date_fin_dispo = $date_fin_dispo;
    }

    /**
     * Get the value of excursion
     */ 
    public function getExcursion(): ?int
    {
        return $this->id_excursion;
    }

    /**
     * Set the value of excursion
     *
     */ 
    public function setExcursion(?int $id_excursion): void
    {
        $this->id_excursion = $id_excursion;
    }

    /**
     * Get the value of guide
     */ 
    public function getGuide(): ?int
    {
        return $this->id_guide;
    }

    /**
     * Set the value of guide
     *
     */ 
    public function setGuide(?int $id_guide): void
    {
        $this->id_guide = $id_guide;
    }

    /**
     * Get the value of heure_debut
     */
    public function getHeure_debut(): ?DateTime
    {
        return $this->heure_debut;
    }

    /**
     * Set the value of heure_debut
     */
    public function setHeure_debut(?DateTime $heure_debut): void
    {
        $this->heure_debut = $heure_debut;
    }
}