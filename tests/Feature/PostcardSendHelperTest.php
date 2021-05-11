<?php

namespace Tests\Feature;

use App\Exceptions\ClickSendException;
use App\Postcards\PostcardSendHelper;
use Tests\TestCase;

class PostcardSendHelperTest extends TestCase
{

    /** @test **/
    public function it_throws_click_send_exception_for_invalid_pdf_url(): void
    {
        // Assert
        $this->expectException(ClickSendException::class);
        $this->expectExceptionMessage("Your postcard file URL can't be found. Please try again.");

        // Arrange
        $postcardSendHelper = new PostcardSendHelper();

        // Act
        $postcardSendHelper->send(collect($this->getTestRecipient()), ['', '']);

    }

    /** @test * */
    public function it_throws_click_send_exception_for_invalid_recipient(): void
    {
        // Assert
        $this->expectException(ClickSendException::class);
        $this->expectExceptionMessage('Recipient Error: INVALID_COUNTRY');

        // Arrange
        $postcardSendHelper = new PostcardSendHelper();

        // Act
        $postcardSendHelper->send(collect($this->getTestRecipient(['country' => 'Neverland'])), ['https://postcards.c6digital.io/pdfs/static/ban-fossil-fuel-advertisements/postcard-front-deep-sea-mining.pdf', 'https://postcards.c6digital.io/pdfs/static/ban-fossil-fuel-advertisements/postcard-front-deep-sea-mining.pdf']);

    }

    private function getTestRecipient(array $dataToMerge = []): array
    {
        return [
             array_merge([
                'name' => 'Ursula von der Leyen',
                'address_line_1' => 'Representation of the European Commission',
                'address_line_2' => 'Rue de la Loi',
                'city' => 'Brussels',
                'state' => 'Brussels',
                'zip' => '11111',
                'country' => 'Belgiumm',
                'return_address_id' => 1,
                'schedule' => 0,
            ], $dataToMerge)
        ];
    }
}
