<?php

namespace Tests\Feature;

use ErrorException;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class GeneratePostcardFrontPdfTest extends TestCase
{
    /** @test **/
    public function it_creates_pdf_from_artisan_command_using_local_image(): void
    {
        $this->withoutExceptionHandling();
        // Act
    	$this->artisan('postcards:generate-front-pdf postcard-test-front-image.png');

    	// Assert
        $this->assertFileExists(public_path('pdfs/postcard-test-front-image.pdf'));

        // Clean up
        File::delete(public_path('pdfs/postcard-test-front-image.pdf'));
    }

    /** @test **/
    public function it_creates_pdf_from_artisan_command_using_local_image_with_different_extension(): void
    {
        // Act
        $this->artisan('postcards:generate-front-pdf postcard-test-front-image.jpg');

        // Assert
        $this->assertFileExists(public_path('pdfs/postcard-test-front-image.pdf'));

        // Clean up
        File::delete(public_path('pdfs/postcard-test-front-image.pdf'));
    }

    /** @test **/
    public function it_creates_pdf_from_artisan_command_using_external_image(): void
    {
        // Act
        $this->artisan('postcards:generate-front-pdf https://i.picsum.photos/id/852/2800/1935.jpg?hmac=wsX1PoihoicA6fZ_1bRq1o0RRbLdq7PP4q6QxCWUWtg');

        // Assert
        $this->assertFileExists(public_path('pdfs/1935.pdf'));

        // Clean up
        File::delete(public_path('pdfs/1935.pdf'));
    }

    /** @test **/
    public function it_throws_error_if_image_not_given(): void
    {
        $this->expectException(ErrorException::class);

        // Act
        $this->artisan('postcards:generate-front-pdf image-not-given');
    }
}
