<?php


namespace App\Postcards\Campaigns;


interface CampaignContract
{
    public function getName(): string;

    public function getRecipients(): array;

    public function getCovers(): array;
}
