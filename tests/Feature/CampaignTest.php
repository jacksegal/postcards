<?php

namespace Tests\Feature;

use ClickSend\Model\PostRecipient;
use Tests\TestCampaign;
use Tests\TestCampaignUsingSupporterInfo;
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

    /** @test **/
    public function it_provides_the_get_recipients_method_with_current_supporter_info(): void
    {
        // Arrange
        $campaign = new TestCampaignUsingSupporterInfo();

        // Act
        $recipients = $campaign->createRecipients(['name' => 'Custom Name']);

        // Assert
        $this->assertInstanceOf(PostRecipient::class, $recipients->first());
        $this->assertEquals('Custom Name', $recipients->first()->getAddressName());
    }

}
