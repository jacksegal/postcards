<?php


namespace Tests;


use App\Postcards\Campaigns\Campaign;

class TestCampaign extends Campaign
{
    private string $name = 'Test Campaign';

    public function getName(): string
    {
        return $this->name;
    }

    public function getRecipients(array $supporterInfo = []): array
    {
        return [
            [
                'name' => 'Ursula von der Leyen',
                'address_line_1' => 'Representation of the European Commission',
                'address_line_2' => 'Rue de la Loi',
                'city' => 'Brussels',
                'state' => 'Brussels',
                'zip' => '11111',
                'country' => 'Belgium',
                'return_address_id' => 1,
                'schedule' => 0,
            ]
        ];
    }
}
