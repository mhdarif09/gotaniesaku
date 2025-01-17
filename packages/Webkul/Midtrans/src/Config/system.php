<?php

return [
    [
        'key'    => 'sales.payment_methods.midtrans',
        'name'   => 'Midtrans',
        'sort'   => 2,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.admin.system.title',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ], 
            [
                'name'          => 'description',
                'title'         => 'admin::app.admin.system.description',
                'type'          => 'textarea',
                'channel_based' => false,
                'locale_based'  => true,
            ], 
            [
                'name'          => 'active',
                'title'         => 'admin::app.admin.system.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ],
            [
                'name'          => 'server_key',
                'title'         => 'admin::app.admin.system.server_key',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => false,
            ],
            [
                'name'          => 'client_key',
                'title'         => 'admin::app.admin.system.client_key',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => false,
            ],
            [
                'name'          => 'is_production',
                'title'         => 'admin::app.admin.system.is_production',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => false,
            ]
        ]
    ]
];
