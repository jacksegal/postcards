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
}
