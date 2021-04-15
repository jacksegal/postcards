<?php

namespace App\Console\Commands;

use Dompdf\Dompdf;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class GeneratePostcardFrontPdf extends Command
{
    protected $signature = 'postcards:generate-front-pdf {imageName}';

    protected $description = 'Generate a PDF for the front of a postcard';

    public function handle()
    {
        $imagePath = public_path('images/' . $this->argument('imageName') . '.png');
        $imageUrl = asset('images/' . $this->argument('imageName') . '.png');

        throw_if(!File::exists($imagePath),\ErrorException::class, 'Image does not exist here: ' .$imagePath);

        $html = view('pdf.template', ['image' => $imageUrl])->render();

        $dompdf = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setIsRemoteEnabled(true);
        $dompdf->setOptions($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A5', 'landscape');
        $dompdf->render();
        $output = $dompdf->output();

        File::put(public_path('pdfs/' . $this->argument('imageName') . '.pdf'), $output);
    }
}
