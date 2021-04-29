<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class GeneratePostcardFrontPdfTest extends TestCase
{
    /** @test **/
    public function it_fails_if_campaign_name_option_not_given(): void
    {
        $this->artisan('postcards:generate-front-pdf postcard-test-front-image.png')
            ->expectsOutput('Campaign name options is missing')
            ->assertExitCode(0);
    }

    /** @test **/
    public function it_creates_pdf_from_artisan_command_using_local_image(): void
    {
        // Act
    	$this->artisan('postcards:generate-front-pdf postcard-test-front-image.png --campaign-name=my-campaign');

    	// Assert
        $this->assertFileExists(public_path('pdfs/static/my-campaign/postcard-test-front-image.pdf'));

        // Clean up
        File::deleteDirectory(public_path('pdfs/static/my-campaign'));
    }

    /** @test **/
    public function it_creates_pdf_from_artisan_command_using_local_image_with_different_extension(): void
    {
        // Act
        $this->artisan('postcards:generate-front-pdf postcard-test-front-image.jpg --campaign-name=my-campaign');

        // Assert
        $this->assertFileExists(public_path('pdfs/static/my-campaign/postcard-test-front-image.pdf'));

        // Clean up
        File::deleteDirectory(public_path('pdfs/static/my-campaign'));
    }

    /** @test **/
    public function it_creates_pdf_from_artisan_command_using_external_image(): void
    {
        // Act
        $this->artisan('postcards:generate-front-pdf https://i.picsum.photos/id/852/2800/1935.jpg?hmac=wsX1PoihoicA6fZ_1bRq1o0RRbLdq7PP4q6QxCWUWtg --campaign-name=my-campaign');

        // Assert
        $this->assertFileExists(public_path('pdfs/static/my-campaign/1935.pdf'));

        // Clean up
        File::deleteDirectory(public_path('pdfs/static/my-campaign'));
    }
}
