<?php 

return [
    'utilisateur' => [
        'afficherDashboard' => [
            'label' => 'Tableau de Bord',
            'parent' => 'accueil'
        ]
    ],
    'excursion' => [
        'lister' => [
            'label' => 'Excursions',
            'parent' => 'accueil'
        ],
        'listerByGuide' => [
            'label' => 'Excursions',
            'parent' => 'utilisateur.afficherDashboard'
        ],
        'afficherCreer' => [
            'label' => 'Créer une Excursion',
            'parent' => 'excursion.listerByGuide'
        ],
        'afficherModifier' => [
            'label' => 'Modifier :excursion_nom',
            'parent' => 'excursion.listerByGuide'
        ],
        'afficher' => [
            'label' => 'Information de l\'Excursion',
            'parent' => 'excursion.listerByGuide'
        ]
    ],
    'visite' => [
        'lister' => [
            'label' => 'Visites',
            'parent' => 'utilisateur.afficherDashboard'
        ],
        'redirectCreer' => [
            'label' => 'Créer une Visite',
            'parent' => 'visite.lister'
        ],
        'redirectModifier' => [
            'label' => 'Modifier la Visite',
            'parent' => 'visite.lister'
        ]
    ],
    'engagement' => [
        'afficherCreer' => [
            'label' => 'Créer un Engagement',
            'parent' => 'excursion.afficher'
        ]
    ],
    'voyageur' => [
        'afficher' => [
            'label' => 'Mes Informations',
            'parent' => 'utilisateur.afficherDashboard'
        ],
    ],
    'guide' => [
        'afficherInformation' => [
            'label' => 'Mes Informations',
            'parent' => 'utilisateur.afficherDashboard'
        ],
        'afficherPlanning' => [
            'label' => 'Mon Planning',
            'parent' => 'utilisateur.afficherDashboard'
        ],
    ],
    'carnetVoyage' => [
        'lister' => [
            'label' => 'Carnets de Voyage',
            'parent' => 'accueil'
        ],
        'listerParVoyageur' => [
            'label' => 'Carnets de Voyage',
            'parent' => 'utilisateur.afficherDashboard'
        ],
        'creer' => [
            'label' => 'Créer un Carnet de Voyage',
            'parent' => 'carnetVoyage.listerParVoyageur'
        ]
    ],
    'post' => [
        'lister' => [
            'label' => 'Publications',
            'parent' => 'accueil'
        ],
        'listerParCarnet' => [
            'label' => 'Mes Publications',
            'parent' => 'carnetVoyage.listerParVoyageur'
        ],
        'afficher' => [
            'label' => 'Information de la Publication',
            'parent' => 'post.listerParCarnet'
        ]
    ],
    'reservation' => [
        'afficherPlanning' => [
            'label' => 'Mon Planning',
            'parent' => 'utilisateur.afficherDashboard'
        ]
    ],
];