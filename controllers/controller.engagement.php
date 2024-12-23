<?php

class ControllerEngagement extends BaseController
{

    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    public function afficherCreer(int $id): void
    {
        $ExcursionDao = new ExcursionDao();
        $excursion = $ExcursionDao->findAssoc($id);

        echo $this->getTwig()->render('creer_engagement.html.twig', [
            'excursion' => $excursion
        ]);
    }

    public function creer(): void
    {
        if (!empty($this->getPost())) {
            $data = [
                'date_debut_dispo' => $this->getPost()['date_debut_dispo'] ?? '',
                'date_fin_dispo' => $this->getPost()['date_fin_dispo'] ?? '',
                'heure_debut' => $this->getPost()['heure_debut'] ?? '',
                'id_excursion' => $this->getPost()['id_excursion'] ?? '',
                'id_guide' => $this->getPost()['id_guide'] ?? '',
            ];

            if (empty($data['date_debut_dispo']) || empty($data['date_fin_dispo']) || empty($data['heure_debut']) || empty($data['id_excursion']) || empty($data['id_guide'])) {
                throw new InvalidArgumentException("Tous les chemps sont obligatoires.");
            }

            $dateDebut = new DateTime($data['date_debut_dispo']);
            $dateFin = new DateTime($data['date_fin_dispo']);
            if ($dateDebut > $dateFin) {
                throw new InvalidArgumentException("La date de début de l'engagement ne peut pas être après la date de fin de l'engagement.");
            }

            $excursionDao = new ExcursionDao();
            $excursion = $excursionDao->findAssoc($data['id_excursion']);
            if (!$excursion) {
                throw new Exception("L'ID de l'excursion fournie n'est pas valide.");
            }

            try {
                $engagement = new Engagement();
                $engagement->setDate_debut($dateDebut);
                $engagement->setDate_fin($dateFin);
                $engagement->setExcursion($excursion->getId());
                $engagement->setGuide($data['id_guide']);
                $engagement->setHeure_debut(new DateTime($data['heure_debut']));

                $engagementDao = new EngagementDao();
                $success = $engagementDao->creer($engagement);

                if ($success) {
                    echo "Nouveau engagement créé avec succès.";
                    $this->redirect('excursion', 'listerByGuide', ['id' => $data['id_guide']]);
                } else {
                    throw new Exception("Erreur lors de la création de l'engagement.");
                }
            } catch (Exception $e) {
                error_log($e->getMessage());
                echo "Erreur: " . $e->getMessage();
            }
        } else {
            echo "Aucune donnée n'a été soumise.";
        }
    }
}
