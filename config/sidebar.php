<?php

return [
    'modules' => [
        [
            'title' => 'Dashboard',
            'icon' => 'home',
            'route' => 'dashboard',
            'active' => 'dashboard*',
            'can' => 'view-dashboard',
        ],
        [
            'title' => 'Docentes',
            'icon' => 'users',
            'route' => 'teachers.index',
            'active' => 'teachers*',
            'can' => 'view-teachers'
        ],
    ],
];
