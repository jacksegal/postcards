<?php


namespace App\Postcards\Campaigns;


use Illuminate\Support\Collection;

interface CampaignContract
{

    public function getRecipients(): array;

    public function createRecipients(): Collection;

}
