<?php

namespace Tests\Feature;

use Tests\TestCase;

class GeneratePostcardFrontPdfTest extends TestCase
{
    /** @test **/
    public function it_creates_pdf_from_running_artisan_command(): void
    {
        $this->withoutExceptionHandling();
        // Act
    	$this->artisan('postcards:generate-front-pdf postcard-front-bycatch');

    	// Assert
        $this->assertFileExists(public_path('pdfs/postcard-front-bycatch.pdf'));
    }
}
