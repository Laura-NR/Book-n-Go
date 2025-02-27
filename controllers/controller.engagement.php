<?php

/**
 * @file controller.engagement.php
 * @class ControllerEngagement
 * @brief Classe du contrôleur pour la gestion des engagements
 */
class ControllerEngagement extends BaseController
{

    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    /**
     * @brief Affiche le formulaire de création d'un engagement pour une excursion.
     *
     * Cette méthode affiche le formulaire de création d'un engagement pour une excursion en fonction de son ID.
     *
     * @param int $id L'ID de l'excursion pour laquelle l'engagement sera créé.
     *
     * @return void
     */
    public function afficherCreer(int $id): void
    {
        $ExcursionDao = new ExcursionDao();
        $excursion = $ExcursionDao->findAssoc($id);

        $messages = $_SESSION['messages_engagements'] ?? [];
        unset($_SESSION['messages_engagements']);

        echo $this->getTwig()->render('creer_engagement.html.twig', [
            'excursion' => $excursion,
            'messages_engagements' => $messages
        ]);
    }

    /**
     * @brief Crée un engagement pour une excursion.
     *
     * Cette méthode crée un engagement pour une excursion en fonction des données soumises.
     *
     * @throws InvalidArgumentException Si les données soumises sont invalides.
     * @throws Exception Si une erreur se produit lors de la création de l'engagement.
     *
     * @return void
     */
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

            // Si les données sont invalides, on lance une exception
            if (empty($data['date_debut_dispo']) || empty($data['date_fin_dispo']) || empty($data['heure_debut']) || empty($data['id_excursion']) || empty($data['id_guide'])) {
                // throw new InvalidArgumentException("Tous les chemps sont obligatoires.");
                $_SESSION['messages_engagements'][] = ['type' => 'danger', 'message' => 'Tous les chemps sont obligatoires.'];
                $this->redirect('engagement', 'afficherCreer', ['id' => $data['id_excursion']]);
                return;
            }

            if (!empty($data['date_debut_dispo']) && !empty($data['date_fin_dispo'])) {
                $dateDebut = new DateTime($data['date_debut_dispo']);
                $dateFin = new DateTime($data['date_fin_dispo']);
            
                if ($dateDebut > $dateFin) {
                    $_SESSION['messages_engagements'][] = ['type' => 'danger', 'message' => 'La date de début ne peut pas être après la date de fin.'];
                    $this->redirect('engagement', 'afficherCreer', ['id' => $data['id_excursion']]);
                    return;
                }
            } else {
                $dateDebut = null;
                $dateFin = null;
            }
            

            // Vérifier si le guide a déjà un engagement pour pour une autre excursion à la même date 
            $engagementDao = new EngagementDao();
            if ($dateDebut && $dateFin && $engagementDao->conflitsEngagements($data['id_guide'], $dateDebut, $dateFin)) {

                $_SESSION['messages_engagements'][] = ['type' => 'danger', 'message' => 'Vous avez déjà un engagement qui chevauche cette période.'];

                $this->redirect('engagement', 'afficherCreer', ['id' => $data['id_excursion']]);
                return;
            }

            // Création de l'engagement
            $excursionDao = new ExcursionDao();
            $excursion = $excursionDao->findAssoc($data['id_excursion']);
            if (!$excursion) {
                throw new Exception("L'ID de l'excursion fournie n'est pas valide.");
            }

            try {
                $engagement = new Engagement();
                $engagement->setDateDebutDispo($dateDebut);
                $engagement->setDateFinDispo($dateFin);
                $engagement->setExcursion($excursion->getId());
                $engagement->setGuide($data['id_guide']);
                $engagement->setHeureDebut(new DateTime($data['heure_debut']));

                $engagementDao = new EngagementDao();
                $success = $engagementDao->creer($engagement);

                if ($success) {
                    // echo "Nouveau engagement créé avec succès.";
                    $_SESSION['success_engagements'][] = ['type' => 'success', 'message' => 'Nouveau engagement créé avec succès.'];
                    $this->redirect('excursion', 'listerByGuide', ['id' => $data['id_guide']]);
                } else {
                    throw new Exception("Erreur lors de la création de l'engagement.");
                }
                // Si une erreur se produit, on affiche un message d'erreur
            } catch (Exception $e) {
                error_log($e->getMessage());
                echo "Erreur: " . $e->getMessage();
            }
        } else {
            echo "Aucune donnée n'a été soumise.";
        }
    }





}
