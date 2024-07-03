<?php

return [
    /**
     * TODO: Notes
     * This array permit building navigations and other things that depend of models
     * All the modules and his permissions. This is only managed models. The model name is singular and lowercased.
     * Base permission: view, create, update, delete.
     * name if attribute for parent relationship
     * Navigation Group Main => conditions [parent => '']
     * model attribute define if has model.
     * The attibute [table, class]
     */
    'data' => [
        // Administration
        [
            'name' => 'administration',
            'table' => '',
            'model' => '',
            'class' => '',
            'label' => 'Administration',
            'title' => 'Subasta system management',
            'url' => '',
            'icon' => 'mdi-account-cog-outline',
            'fields' => '[]',
            'order' => 1,
            'parent' => '',
            'permissions' => ['view']
        ],
        [
            'name' => 'role',
            'table' => 'roles',
            'model' => 'Role',
            'class' => 'Spatie\\Permission\\Models\\Role',
            'label' => 'Roles',
            'title' => 'Roles',
            'url' => 'role',
            'icon' => 'mdi-account-multiple-outline',
            'fields' => '[]',
            'order' => 1,
            'parent' => 'administration',
            'permissions' => ['create', 'view', 'update', 'delete']
        ],
        [
            'name' => 'user',
            'table' => 'users',
            'model' => 'User',
            'class' => 'App\\Models\\User',
            'label' => 'Users',
            'title' => 'Users',
            'url' => 'user',
            'icon' => 'mdi-account-outline',
            'fields' => '[]',
            'order' => 2,
            'parent' => 'administration',
            'permissions' => ['create', 'view', 'update', 'delete']],
        [
            'name' => 'activity',
            'table' => 'activity_logs',
            'model' => 'Activity',
            'class' => 'App\\Models\\Activity',
            'label' => 'Activity',
            'title' => 'Activity',
            'url' => 'activity',
            'icon' => 'mdi-account-clock-outline',
            'fields' => '[]',
            'order' => 3,
            'parent' => 'administration',
            'permissions' => ['create', 'view', 'update', 'delete']],
        //Persons
        [
            'name' => 'person',
            'table' => '',
            'model' => '',
            'class' => '',
            'label' => 'Persons',
            'title' => 'Persons',
            'url' => '',
            'icon' => 'mdi-account-multiple-outline',
            'fields' => '[]',
            'order' => 2,
            'parent' => '',
            'permissions' => ['view']],
        [
            'name' => 'carrier',
            'table' => 'carriers',
            'model' => 'Carrier',
            'class' => 'App\\Models\\Carrier',
            'label' => 'Carriers',
            'title' => 'Carriers',
            'url' => 'carrier',
            'icon' => 'mdi-human-male',
            'fields' => '[]',
            'order' => 1,
            'parent' => 'person',
            'permissions' => ['create', 'view', 'update', 'delete']],
        [
            'name' => 'client',
            'table' => 'clients',
            'model' => 'Client',
            'class' => 'App\\Models\\Client',
            'label' => 'Clients',
            'title' => 'Clients',
            'url' => 'client',
            'icon' => 'mdi-briefcase-variant-outline',
            'fields' => '[]',
            'order' => 2,
            'parent' => 'person',
            'permissions' => ['create', 'view', 'update', 'delete']],
        //Bearings o Portes
        [
            'name' => 'bearing',
            'table' => 'bearings',
            'model' => 'Bearing',
            'class' => 'App\\Models\\Bearing',
            'label' => 'Bearings',
            'title' => 'Bearings',
            'url' => 'bearing',
            'icon' => 'mdi-cube-outline',
            'fields' => '[]',
            'order' => 3,
            'parent' => '',
            'permissions' => ['create', 'view', 'update', 'delete']],
        // Tareas de validación
        [
            'name' => 'validationtask',
            'table' => 'validation-tasks',
            'model' => 'ValidationTask',
            'class' => 'App\\Models\\ValidationTask',
            'label' => 'Validation Tasks',
            'title' => 'Validation Tasks',
            'url' => 'validation-task',
            'icon' => 'mdi-calendar-multiple-check',
            'fields' => '[]',
            'order' => 4,
            'parent' => '',
            'permissions' => ['create', 'view', 'update', 'delete']],
        // Encoders
        [
            'name' => 'encoder',
            'table' => '',
            'model' => '',
            'class' => '',
            'label' => 'Encoders',
            'title' => 'Encoders',
            'url' => '',
            'icon' => 'mdi-codepen',
            'fields' => '[]',
            'order' => 5,
            'parent' => '',
            'permissions' => ['view']],
        [
            'name' => 'country',
            'table' => 'countries',
            'model' => 'Country',
            'class' => 'App\\Models\\Country',
            'label' => 'Countries',
            'title' => 'Countries',
            'url' => 'country',
            'icon' => 'map-marker-multiple-outline',
            'fields' => '[]',
            'order' => 1,
            'parent' => 'encoder',
            'permissions' => ['view']],
        [
            'name' => 'state',
            'table' => 'states',
            'model' => 'State',
            'class' => 'App\\Models\\State',
            'label' => 'States',
            'title' => 'States',
            'url' => 'state',
            'icon' => 'map-marker-outline',
            'fields' => '[]',
            'order' => 2,
            'parent' => 'encoder',
            'permissions' => ['view']],
        [
            'name' => 'city',
            'table' => 'cities',
            'model' => 'City',
            'class' => 'App\\Models\\City',
            'label' => 'Cities',
            'title' => 'Cities',
            'url' => 'city',
            'icon' => 'map-marker-radius-outline',
            'fields' => '[]',
            'order' => 3,
            'parent' => 'encoder',
            'permissions' => ['view']],
        
    ]

];
