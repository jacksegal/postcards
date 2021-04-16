<?php

namespace Tests\Feature;

use ErrorException;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class GeneratePostcardFrontPdfTest extends TestCase
{
    /** @test **/
    public function it_creates_pdf_from_running_artisan_command(): void
    {
        // Act
    	$this->artisan('postcards:generate-front-pdf postcard-test-front-image');

    	// Assert
        $this->assertFileExists(public_path('pdfs/postcard-test-front-image.pdf'));

        // Clean up
        File::delete(public_path('pdfs/postcard-test-front-image.pdf'));
    }

    /** @test **/
    public function it_throws_error_if_image_not_given(): void
    {
        $this->expectException(ErrorException::class);

        // Act
        $this->artisan('postcards:generate-front-pdf image-not-given');

    }
}
