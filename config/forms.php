<?php

return [
    /**
     * Forms initialization by module
     */
    'default' => [
        // Register carrier
        [
            'name' => 'carrier_register',
            'module_name' => 'carrier',
            'label' => 'Register new carrier',
            'type' => \App\Enums\FormTypes::form->value,
            'route' => 'register/carrier',
            'options' => '{}',
            'default_value' => '{}',
            'fields' => [
                [
                    'name' => 'avatar',
                    'rules' => 'image|nullable|mimes:jpeg,png,jpg,gif|size:2048',
                    'position' => 1,
                    'step' => 1
                ],
                [
                    'name' => 'name',
                    'rules' => 'required|string|max:50',
                    'position' => 2,
                    'step' => 1
                ],
                [
                    'name' => 'surname',
                    'rules' => 'nullable|string|max:50',
                    'position' => 3,
                    'step' => 1
                ],
                [
                    'name' => 'email',
                    'rules' => 'required|string|max:255|unique:users',
                    'position' => 4,
                    'step' => 1
                ],
                [
                    'name' => 'username',
                    'rules' => 'required|string|max:50|unique:users',
                    'position' => 5,
                    'step' => 1
                ],
                [
                    'name' => 'password',
                    'rules' => 'required|string|min:6|confirmed',
                    'position' => 6,
                    'step' => 1
                ],
                [
                    'name' => 'address',
                    'rules' => 'required|string',
                    'position' => 1,
                    'step' => 2
                ],
                [
                    'name' => 'phone',
                    'rules' => 'required|string',
                    'position' => 2,
                    'step' => 2
                ],
                [
                    'name' => 'date_of_birth',
                    'rules' => 'required|date',
                    'position' => 3,
                    'step' => 2
                ],
                [
                    'name' => 'company_name',
                    'rules' => 'nullable|string',
                    'position' => 4,
                    'step' => 2
                ],
                [
                    'name' => 'industry',
                    'rules' => 'nullable|string',
                    'position' => 5,
                    'step' => 2
                ],
                [
                    'name' => 'about_me',
                    'rules' => 'nullable|string',
                    'position' => 6,
                    'step' => 2
                ],
                [
                    'name' => 'gender',
                    'rules' => 'required|in:unknown,male,female',
                    'position' => 7,
                    'step' => 2
                ],
                [
                    'name' => 'transportation_card',
                    'rules' => 'nullable|file|size:10240',
                    'position' => 1,
                    'step' => 3
                ],
                [
                    'name' => 'end_date_transportation_card',
                    'rules' => 'nullable|date',
                    'position' => 2,
                    'step' => 3
                ],
                [
                    'name' => 'merchandise_insurance',
                    'rules' => 'nullable|file|size:10240',
                    'position' => 3,
                    'step' => 2
                ],
                [
                    'name' => 'end_date_merchandise_insurance',
                    'rules' => 'nullable|date',
                    'position' => 4,
                    'step' => 3
                ],
                [
                    'name' => 'high_social_security',
                    'rules' => 'nullable|file|size:10240',
                    'position' => 1,
                    'step' => 3
                ],
                [
                    'name' => 'end_date_high_social_security',
                    'rules' => 'nullable|date',
                    'position' => 2,
                    'step' => 3
                ],
                [
                    'name' => 'payment_current',
                    'rules' => 'nullable|file|size:10240',
                    'position' => 1,
                    'step' => 3
                ],
                [
                    'name' => 'end_date_payment_current',
                    'rules' => 'nullable|date',
                    'position' => 2,
                    'step' => 3
                ],
                [
                    'name' => 'vehicle_insurance',
                    'rules' => 'nullable|file|size:10240',
                    'position' => 1,
                    'step' => 3
                ],
                [
                    'name' => 'end_date_vehicle_insurance',
                    'rules' => 'nullable|date',
                    'position' => 2,
                    'step' => 3
                ],
                [
                    'name' => 'itv',
                    'rules' => 'nullable|file|size:10240',
                    'position' => 1,
                    'step' => 3
                ],
                [
                    'name' => 'end_date_itv',
                    'rules' => 'nullable|date',
                    'position' => 2,
                    'step' => 3
                ]
            ]
        ],
        // Register Client
        [
            'name' => 'client_register',
            'module_name' => 'client',
            'label' => 'Register new client',
            'type' => \App\Enums\FormTypes::form->value,
            'route' => 'register/client',
            'options' => '{}',
            'default_value' => '{}',
            'fields' => [
                [
                    'name' => 'avatar',
                    'rules' => 'image|nullable|mimes:jpeg,png,jpg,gif|size:2048',
                    'position' => 1,
                    'step' => 1
                ],
                [
                    'name' => 'name',
                    'rules' => 'required|string|max:50',
                    'position' => 2,
                    'step' => 1
                ],
                [
                    'name' => 'surname',
                    'rules' => 'nullable|string|max:50',
                    'position' => 3,
                    'step' => 1
                ],
                [
                    'name' => 'email',
                    'rules' => 'required|string|max:255|unique:users',
                    'position' => 4,
                    'step' => 1
                ],
                [
                    'name' => 'username',
                    'rules' => 'required|string|max:50|unique:users',
                    'position' => 5,
                    'step' => 1
                ],
                [
                    'name' => 'password',
                    'rules' => 'required|string|min:6|confirmed',
                    'position' => 6,
                    'step' => 1
                ],
                [
                    'name' => 'address',
                    'rules' => 'required|string',
                    'position' => 1,
                    'step' => 2
                ],
                [
                    'name' => 'phone',
                    'rules' => 'required|string',
                    'position' => 2,
                    'step' => 2
                ],
                [
                    'name' => 'date_of_birth',
                    'rules' => 'required|date',
                    'position' => 3,
                    'step' => 2
                ],
                [
                    'name' => 'company_name',
                    'rules' => 'nullable|string',
                    'position' => 4,
                    'step' => 2
                ],
                [
                    'name' => 'industry',
                    'rules' => 'nullable|string',
                    'position' => 5,
                    'step' => 2
                ],
                [
                    'name' => 'about_me',
                    'rules' => 'nullable|string',
                    'position' => 6,
                    'step' => 2
                ],
                [
                    'name' => 'gender',
                    'rules' => 'required|in:unknown,male,female',
                    'position' => 7,
                    'step' => 2
                ]
            ]
        ],
    ]
];
