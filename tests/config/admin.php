<?php

return [
    /*
     * Laravel-admin database setting.
     */
    'database' => [

        // Database connection for following tables.
        'connection' => '',

        // User tables and model.
        'users_table' => 'admin_users',

        // Role table and model.
        'roles_table' => 'admin_roles',

        // Permission table and model.
        'permissions_table' => 'admin_permissions',

        // Menu table and model.
        'menu_table' => 'admin_menu',

        // Pivot table for table above.
        'operation_log_table'    => 'admin_operation_log',
        'user_permissions_table' => 'admin_user_permissions',
        'role_users_table'       => 'admin_role_users',
        'role_permissions_table' => 'admin_role_permissions',
        'role_menu_table'        => 'admin_role_menu',
    ],
];
