<?php


namespace App\Postcards\Campaigns;


class BanFossilFuelAdvertisements extends Campaign implements CampaignContract
{

    public function getName(): string
    {
       return 'Ban Fossil Fuel Advertisements';
    }

    public function getRecipients(): array
    {
        return [
          'test@test.at'
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
