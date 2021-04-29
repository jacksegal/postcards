<?php

namespace App\Postcards;

use Dompdf\Dompdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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

    public function createPostcardBack(string $campaignSupporterDirectory, string $html): string
    {
        $this
            ->useHtml($html)
            ->outputPath(Storage::disk('campaigns')->path($campaignSupporterDirectory) . '/postcard_back.pdf')
            ->create();

        return Storage::disk('campaigns')->url($campaignSupporterDirectory . '/postcard_back.pdf');
    }

    public function getPostcardFront(string $supporterCampaignDirectory, string $postcardFrontName): string
    {
        File::put(Storage::disk('campaigns')->path($supporterCampaignDirectory .'/postcard_front.pdf'), file_get_contents(asset('pdfs/defaults/ban-fossil-fuel-advertisements/'.$postcardFrontName.'.pdf')));

        return Storage::disk('campaigns')->url($supporterCampaignDirectory .'/postcard_front.pdf');
    }
}
