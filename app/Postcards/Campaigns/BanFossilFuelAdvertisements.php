<?php


namespace App\Postcards\Campaigns;


class BanFossilFuelAdvertisements extends Campaign
{

    public function getRecipients(array $supporterInfo = []): array
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
                'return_address_id' => 1,
                'schedule' => 0,
            ]
        ];
    }

}
