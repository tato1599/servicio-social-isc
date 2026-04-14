<?php

return [
    'modules' => [
        [
            'title' => 'Dashboard',
            'icon' => 'dashboard',
            'route' => 'dashboard',
            'active' => 'dashboard*',
            'can' => 'view-dashboard',
        ],
        [
            'title' => 'Horarios',
            'icon' => 'calendar_month',
            'route' => 'courses.index',
            'active' => 'courses*',
            'can' => 'view-courses',
        ],
        [
            'title' => 'Maestros',
            'icon' => 'person',
            'route' => 'teachers.index',
            'active' => 'teachers*',
            'can' => 'view-teachers',
        ],
        [
            'title' => 'Materias',
            'icon' => 'book',
            'route' => 'subjects.index',
            'active' => 'subjects*',
            'can' => 'view-subjects',
        ],
        [
            'title' => 'Servicio Social',
            'icon' => 'work',
            'route' => '#',
            'active' => 'social*',
            'can' => 'view-social',
        ],
        [
            'title' => 'Reportes',
            'icon' => 'bar_chart',
            'route' => '#',
            'active' => 'reports*',
            'can' => 'view-reports',
        ],
        [
            'title' => 'Configuración',
            'icon' => 'settings',
            'route' => '#',
            'active' => 'settings*',
            'can' => 'view-settings',
        ],
    ],
];
