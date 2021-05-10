<?php

namespace App\Console\Commands;

use App\Postcards\PdfHelper;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GeneratePostcardFrontPdf extends Command
{
    protected $signature = 'postcards:generate-front-pdf {image} {--campaign-name=}';

    protected $description = 'Generate a PDF for the front of a postcard';

    public function handle(): void
    {

        if($this->option('campaign-name')) {

            try {
                [$imageName, $imageUrl] = $this->getImageIfGiven($this->argument('image'));
            } catch (Exception $exception) {
                $this->error('Image does not exist locally or as external URL');
                exit;
            }

            $html = view('pdf.default.front', ['image' => $imageUrl])->render();
            File::isDirectory(public_path('pdfs/static/' . $this->option('campaign-name'))) or File::makeDirectory(public_path('pdfs/static/' . $this->option('campaign-name')));
            $pdfPath = public_path('pdfs/static/' . $this->option('campaign-name') . '/' . $imageName . '.pdf');

            $pdfHelper = new PdfHelper();
            $pdfHelper->useHtml($html)
                ->outputPath($pdfPath)
                ->create();

            $this->info($pdfPath);
        } else {
            $this->error('Campaign name options is missing');
        }
    }

    private function getImageIfGiven(string $image): array
    {
        // Check if given image is a local image
        $imageName = pathinfo($image)['filename'];

        if (File::exists(public_path('images/' . $image))) {
            $imageUrl = asset('images/' . $image);

            return [$imageName, $imageUrl];
        }

        // Check if given image is an external image
        $response = Http::get($image);

        if ($response->status()) {
            $imageName = Str::of($this->argument('image'))->basename()->before('.');

            return [$imageName, $image];
        }

        $this->error('Image does not exist locally or as external URL');

        exit;
    }
}
