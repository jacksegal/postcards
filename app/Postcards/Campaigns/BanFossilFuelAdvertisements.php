<?php


namespace App\Postcards\Campaigns;


class BanFossilFuelAdvertisements extends Campaign
{

    public function getRecipients(): array
    {
        return [
            [
                'name' => 'Ursula von der Leyen',
                'address_line_1' => 'Representation of the European Commission',
                'address_line_2' => 'Rue de la Loi',
                'city' => 'Brussels',
                'state' => 'Brussels',
                'zip' => '1040',
                'country' => 'Belgium',
            ]
        ];
    }

    public function getCovers(): array
    {
        return [
            'path-to-front-pdf',
            'path-to-back-pdf',
        ];
    }
}
