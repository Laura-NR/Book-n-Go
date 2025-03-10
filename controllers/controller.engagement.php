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
        $this->breadcrumbService->buildFromRoute('engagement', 'afficherCreer');

        $ExcursionDao = new ExcursionDao();
        $excursion = $ExcursionDao->findAssoc($id);

        $messages = $_SESSION['messages_engagements'] ?? [];
        unset($_SESSION['messages_engagements']);

        echo $this->getTwig()->render('creer_engagement.html.twig', [
            'excursion' => $excursion,
            'messages_engagements' => $messages,
            'breadcrumb' => $this->breadcrumbService->getItems()
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
                echo "Erreur: " . $e->getMessage();
            }
        } else {
            echo "Aucune donnée n'a été soumise.";
        }
    }


    public function modifier(): void
    {
        var_dump($_POST);

        if (!empty($this->getPost())) {
            $data = [
                'id' => $this->getPost()['id_engagement'] ?? '',
                'date_debut_dispo' => $this->getPost()['date_debut_dispo'] ?? '',
                'date_fin_dispo' => $this->getPost()['date_fin_dispo'] ?? '',
                'heure_debut' => $this->getPost()['heure_debut'] ?? ''
            ];

            if (empty($data['id']) || empty($data['date_debut_dispo']) || empty($data['date_fin_dispo']) || empty($data['heure_debut'])) {
                $_SESSION['messages_eng'][] = ['type' => 'danger', 'message' => 'Tous les champs sont obligatoires.'];
                $this->redirect('reservation', 'afficherPlanning', ['id' => $_SESSION['user_id']]);
                return;
            }

            $dateDebut = new DateTime($data['date_debut_dispo']);
            $dateFin = new DateTime($data['date_fin_dispo']);

            if ($dateDebut > $dateFin) {
                $_SESSION['messages_eng'][] = ['type' => 'danger', 'message' => 'La date de début ne peut pas être après la date de fin.'];
                $this->redirect('reservation', 'afficherPlanning', ['id' => $_SESSION['user_id']]);
                return;
            }

            $engagementDao = new EngagementDao();
            $engagement = $engagementDao->find($data['id']);
            if (!$engagement) {
                $_SESSION['messages_eng'][] = ['type' => 'danger', 'message' => 'Engagement introuvable.'];
                $this->redirect('reservation', 'afficherPlanning', ['id' => $_SESSION['user_id']]);
                return;
            }

            $engagement->setDateDebutDispo($dateDebut);
            $engagement->setDateFinDispo($dateFin);
            $engagement->setHeureDebut(new DateTime($data['heure_debut']));

            if ($engagementDao->modifier($engagement)) {
                $_SESSION['messages_eng'][] = ['type' => 'success', 'message' => 'Engagement modifié avec succès.'];
            } else {
                $_SESSION['messages_eng'][] = ['type' => 'danger', 'message' => 'Erreur lors de la modification de l\'engagement.'];
            }

            $this->redirect('reservation', 'afficherPlanning', ['id' => $_SESSION['user_id']]);
        }
    }


    public function supprimer(int $id): void
    {

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'guide') {
            $_SESSION['messages_alertes'][] = ['type' => 'danger', 'message' => 'Vous n\'êtes pas autorisé à effectuer cette action.'];
            $this->redirect('reservation', 'afficherPlanning', ['id' => $_SESSION['user_id']]);
            return;
        }

        $engagementDao = new EngagementDao($this->getPdo());
        $engagement = $engagementDao->findAssoc($id);
        if (!$engagement) {
            $_SESSION['messages_eng'][] = ['type' => 'danger', 'message' => 'Erreur : Engagement introuvable.'];
            $this->redirect('reservation', 'afficherPlanning', ['id' => $_SESSION['user_id']]);
            exit;
        }

        if ($engagement->getIdGuide() !== $_SESSION['user_id']) {
            $_SESSION['messages_alertes'][] = ['type' => 'danger', 'message' => 'Erreur : Vous n\'êtes pas autorisé à supprimer cette excursion.'];
            $this->redirect('reservation', 'afficherPlanning', ['id' => $_SESSION['user_id']]);
            exit;
        }

        if ($engagementDao->supprimer($id)) {
            $_SESSION['messages_eng'][] = ['type' => 'success', 'message' => 'Engagement supprimé avec succès.'];
        } else {
            $_SESSION['messages_eng'][] = ['type' => 'danger', 'message' => 'Erreur lors de la suppression de l\'engagement.'];
        }

        $this->redirect('reservation', 'afficherPlanning', [
            'id' => $_SESSION['user_id']
        ]);
    }
}
