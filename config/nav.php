<?php

return [

    [
        'icon' => 'nav-icon fas fa-tachometer-alt',
        'route' => 'dashboard.dashboard',
        'title' => 'Dashboard',
        'badge' => 'New',
        'active' => 'dashboard.dashboard'
    ],

    [
        'icon' => 'far fa-circle nav-icon',
        'route' => 'dashboard.categories.index',
        'title' => 'Category',
        'active' => 'dashboard.categories.*'
    ],

    [
        'icon' => 'far fa-circle nav-icon',
        'route' => 'dashboard.products.index',
        'title' => 'Product',
        'active' => 'dashboard.products.*'
    ],

];
