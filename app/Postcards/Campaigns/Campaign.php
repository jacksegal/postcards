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

    private string $campaignDirectoryForSending = '';

    private string $supporterDirectoryForSending = '';

    public function send(array $supporter): void
    {
        $pdfHelper = app(PdfHelper::class);
        $this->supporterDirectoryForSending = $this->createDirectoryForSupporter($supporter['Supporter ID']);

        // Create back pdf
        $pdfHelper->createPostcardBack($this->supporterDirectoryForSending, $this->getPostcardBackHtml());

        // Copy given front PDF to current supporter files
        $postcardFrontPdfPath = $this->getPostcardFrontPdfPath($supporter['Postcard Image']);
        File::put($this->supporterDirectoryForSending .'/postcard_front.pdf', File::get($postcardFrontPdfPath));

        $postcardSendHelper = new PostcardSendHelper;
        $postcardSendHelper->print();

        $this->postSendHook();
    }

    public function postSendHook(): void
    {

    }

    public function getPostcardBackHtml(): string
    {
        return view('pdf.template-default-back', ['message' => $this->message])->render();
    }

    public function getPostcardFrontPdfPath(string $postcardFrontName): string
    {
        return public_path('pdfs/'.$postcardFrontName.'.pdf');
    }

    public function getCampaignDirectoryName(): string
    {
        return $this->campaignDirectoryForSending = now()->format('Y-m-d__H-i-s') . '_' . Str::of(TestCampaign::class)->afterLast('\\')->snake();
    }

    public function createDirectoryForSupporter(string $supporterId): string
    {
        Storage::disk('campaigns')->makeDirectory($this->getCampaignDirectoryName() . '/' . $supporterId);

        return Storage::disk('campaigns')->path($this->campaignDirectoryForSending . '/' . $supporterId);
    }

}
