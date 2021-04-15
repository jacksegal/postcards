<?php

namespace App\Console\Commands;

use App\Postcards\PdfHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GeneratePostcardFrontPdf extends Command
{
    protected $signature = 'postcards:generate-front-pdf {imageName}';

    protected $description = 'Generate a PDF for the front of a postcard';

    public function handle()
    {
        $imagePath = public_path('images/' . $this->argument('imageName') . '.png');
        $imageUrl = asset('images/' . $this->argument('imageName') . '.png');

        throw_if(!File::exists($imagePath), \ErrorException::class, 'Image does not exist here: ' . $imagePath);

        $html = view('pdf.template', ['image' => $imageUrl])->render();

        $pdfHelper = new PdfHelper();
        $pdfHelper->useHtml($html)
            ->path(public_path('pdfs/' . $this->argument('imageName') . '.pdf'))
            ->create();

    }
}
