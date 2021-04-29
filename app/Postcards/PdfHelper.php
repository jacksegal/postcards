<?php

namespace App\Postcards;

use Dompdf\Dompdf;
use Illuminate\Support\Facades\File;

class PdfHelper
{
    private string $html;

    private string $path;

    public function useHtml(string $html): self
    {
        $this->html = $html;

        return $this;
    }

    public function outputPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function create(): void
    {
        $dompdf = app(Dompdf::class);
        $options = $dompdf->getOptions();
        $options->setIsRemoteEnabled(true);
        $dompdf->setOptions($options);
        $dompdf->loadHtml($this->html);
        $dompdf->setPaper('A5', 'landscape');
        $dompdf->render();
        $output = $dompdf->output();

        File::put($this->path, $output);
    }

}
