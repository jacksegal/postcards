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
            'name' => 'Green Homes',
            'class' => \App\Postcards\Campaigns\GreenHomes::class,
        ],
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
        ],
        [
            'name' => 'Test Campaign With Wrong Recipient Country (Nothing gets send)',
            'class' => \Tests\TestCampaignWithWrongCountry::class,
        ]
    ]

];
