<?php

return [
    'groups' => [
        'panel' => [
            'label' => 'Panel administrativo',
            'permissions' => [
                ['key' => 'admin.access', 'name' => 'Acceder al panel', 'description' => 'Permite entrar al área administrativa.'],
                ['key' => 'admin.dashboard.view', 'name' => 'Ver dashboard', 'description' => 'Permite ver el tablero principal del admin.'],
            ],
        ],
        'users' => [
            'label' => 'Usuarios y roles',
            'permissions' => [
                ['key' => 'users.manage', 'name' => 'Gestionar usuarios', 'description' => 'Permite listar y cambiar roles de usuarios.'],
                ['key' => 'roles.manage', 'name' => 'Gestionar roles', 'description' => 'Permite crear, editar y eliminar roles y permisos.'],
            ],
        ],
        'orders' => [
            'label' => 'Pedidos',
            'permissions' => [
                ['key' => 'orders.manage', 'name' => 'Gestionar pedidos', 'description' => 'Permite ver y actualizar pedidos.'],
            ],
        ],
        'inventory' => [
            'label' => 'Inventario',
            'permissions' => [
                ['key' => 'inventory.manage', 'name' => 'Gestionar inventario', 'description' => 'Permite ver y actualizar inventario.'],
            ],
        ],
        'reports' => [
            'label' => 'Reportes',
            'permissions' => [
                ['key' => 'reports.view', 'name' => 'Ver reportes', 'description' => 'Permite visualizar reportes y métricas.'],
            ],
        ],
        'settings' => [
            'label' => 'Configuración',
            'permissions' => [
                ['key' => 'settings.manage', 'name' => 'Gestionar configuración', 'description' => 'Permite editar la configuración global.'],
            ],
        ],
        'plugins' => [
            'label' => 'Complementos',
            'permissions' => [
                ['key' => 'plugins.manage', 'name' => 'Gestionar complementos', 'description' => 'Permite administrar extensiones del sistema.'],
            ],
        ],
    ],
];
