<?php

namespace Tests\Feature;

use ClickSend\Model\PostRecipient;
use Tests\TestCampaign;
use Tests\TestCase;

class CampaignTest extends TestCase
{
    /** @test * */
    public function it_creates_recipient_objects_from_recipients(): void
    {
        // Arrange
        $campaign = new TestCampaign();

        // Act
        $recipients = $campaign->createRecipients();

        // Assert
        $this->assertInstanceOf(PostRecipient::class, $recipients->first());
        $this->assertEquals('Brussels', $recipients->first()->getAddressCity());
    }

}
