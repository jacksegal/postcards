<?php


namespace Tests;


use App\Postcards\Campaigns\Campaign;

class TestCampaign extends Campaign
{

    public function getSupporterDirectoryName(): string
    {
        return $this->supporterInfo['Supporter ID'];
    }

    public function getRecipients(): array
    {
        return [
            [
                'name' => 'Ursula von der Leyen',
                'address_line_1' => 'Representation of the European Commission',
                'address_line_2' => 'Rue de la Loi',
                'city' => 'Brussels',
                'state' => 'Brussels',
                'zip' => '11111',
                'country' => 'BE',
                'return_address_id' => 99727,
                'schedule' => 0,
            ]
        ];
    }
}
