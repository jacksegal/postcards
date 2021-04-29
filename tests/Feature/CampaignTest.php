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

    /** @test */
    public function it_uses_recipients_from_csv_instead_if_given(): void
    {
        // Arrange
        $campaign = new TestCampaign();

        // Act
        $recipients = $campaign->createRecipients($this->getCsvRowWithParticipants());

        // Assert
        $this->assertInstanceOf(PostRecipient::class, $recipients->first());
        $this->assertEquals('Overwritten Brussels', $recipients->first()->getAddressCity());
    }

    private function getCsvRowWithParticipants(): array
    {
        return [
            "Supporter ID" => "194764356",
            "Supporter Email" => "jack@c6digital.io",
            "Date Sent" => "05/04/2021 04:01",
            "Subject" => "Ban fossil fuel ads!",
            "Message" => "",
            "Salutation" => "Dear Ms von der Leyen",
            "Organization" => "President of the European Commission",
            "Position Held" => "",
            "Contact Title" => "Ms",
            "Contact First Name" => "Ursula",
            "Contact Last Name" => "von der Leyen",
            "Supporter Title" => "",
            "Supporter First Name" => "Jack",
            "Supporter Last Name" => "Segal",
            "Supporter Address 1" => "148 Rogate House",
            "Supporter Address 2" => "London",
            "Supporter City" => "",
            "Supporter Region" => "",
            "Supporter Postal Code" => "E5 8QX",
            "Supporter Country" => "",
            "Postcard Image" => "postcard-front-bycatch",
            "Recipients" => [
                [
                    'name' => 'Ursula von der Leyen',
                    'address_line_1' => 'Representation of the European Commission',
                    'address_line_2' => 'Rue de la Loi',
                    'city' => 'Overwritten Brussels',
                    'state' => 'Brussels',
                    'zip' => '11111',
                    'country' => 'Belgium',
                    'return_address_id' => 1,
                    'schedule' => 0,
                ]
            ]
        ];
    }
}
