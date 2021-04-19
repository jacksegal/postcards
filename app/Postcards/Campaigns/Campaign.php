<?php

namespace App\Postcards\Campaigns;

use App\Postcards\PdfHelper;
use App\Postcards\PostcardSendHelper;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCampaign;

abstract class Campaign
{
    public string $message = '';

    public function send(array $supporter): void
    {
        $pdfHelper = app(PdfHelper::class);
        $campaignDirectory = $this->createDirectoryForSupporter($supporter['Supporter ID']);

        // Create back pdf
        $pdfHelper->createPostcardBack($supporter, $campaignDirectory);

        // Copy given front PDF to current supporter files
        $postcardFrontPdfPath = $this->getPostcardFrontPdfPath($supporter['Postcard Image']);
        Storage::disk('campaigns')->put($campaignDirectory . '/' . $supporter['Supporter ID'].'/postcard_front.pdf', File::get($postcardFrontPdfPath));

        $postcardSendHelper = new PostcardSendHelper;
        $postcardSendHelper->print();
    }

    public function getPostcardBackHtml(): string
    {
        return view('pdf.template-default-back', ['message' => $this->message])->render();
    }

    public function getPostcardFrontPdfPath(string $postcardFrontName): string
    {
        return public_path('pdfs/'.$postcardFrontName.'.pdf');
    }

    public function createDirectoryForSupporter(string $supporterId): string
    {
        $campaignDirectory = now()->format('Y-m-d__H-i-s') . '_' . Str::of(TestCampaign::class)->afterLast('\\')->snake();
        Storage::disk('campaigns')->makeDirectory($campaignDirectory . '/' . $supporterId);

        return Storage::disk('campaigns')->path($campaignDirectory . '/' . $supporterId);
    }

}
