<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Given Campaigns
    |--------------------------------------------------------------------------
    |
    |
    */

    'campaigns' => [
        [
            'name' => 'Transform Transport',
            'class' => \App\Postcards\Campaigns\TransformTransport::class,
        ],
        [
            'name' => 'Ban Fossil Fuel Advertisements',
            'class' => \App\Postcards\Campaigns\BanFossilFuelAdvertisements::class,
        ],
        [
            'name' => 'Test Campaign (Nothing gets send)',
            'class' => \Tests\TestCampaign::class,
        ]
    ]

];
