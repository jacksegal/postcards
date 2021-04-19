<?php

namespace App\Postcards\Campaigns;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCampaign;

abstract class Campaign
{
    public string $message = '';

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
