<?php


namespace App\Postcards\Campaigns;


interface CampaignContract
{

    public function getRecipients(): array;

    public function getCovers(): array;
}
