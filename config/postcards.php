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
            'name' => 'Ban Fossil Fuel Advertisements',
            'class' => \App\Postcards\Campaigns\BanFossilFuelAdvertisements::class,
        ],
        [
            'name' => 'Test Campaign (Nothing gets send)',
            'class' => \Tests\TestCampaign::class,
        ]
    ]

];
