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
        $campaign = new TestCampaign();

        $this->assertInstanceOf(PostRecipient::class, $campaign->createRecipients()->first());
    }
}
