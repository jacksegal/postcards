<?php

namespace Tests\Unit;

use App\Postcards\Campaigns\BanFossilFuelAdvertisements;
use PHPUnit\Framework\TestCase;

class CampaignTest extends TestCase
{
    /** @test **/
    public function it_returns_name_of_campaign(): void
    {
    	// Arrange
    	dd($campaign = new BanFossilFuelAdvertisements);

    	// Assert
        $this->assertEquals('Ban Fossil Fuel Advertisements', $campaign->getName());
    }
}
