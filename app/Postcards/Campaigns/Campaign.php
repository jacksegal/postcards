<?php

namespace App\Postcards\Campaigns;

use App\Postcards\PdfHelper;
use App\Postcards\PostcardSendHelper;
use ClickSend\Model\PostRecipient;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCampaign;

abstract class Campaign implements CampaignContract
{
    public string $message = '';

    private string $campaignDirectoryForSending = '';

    private string $supporterDirectoryForSending = '';

    public function createRecipients(): Collection
    {
        return collect($this->getRecipients())
            ->map(function(array $recipientInfo){
                $recipient = new PostRecipient();
                $recipient->setAddressName($recipientInfo['name']);
                $recipient->setAddressLine1($recipientInfo['address_line_1']);
                $recipient->setAddressLine2($recipientInfo['address_line_2']);
                $recipient->setaddressCity($recipientInfo['city']);
                $recipient->setaddressState($recipientInfo['state']);
                $recipient->setAddressPostalCode($recipientInfo['zip']);
                $recipient->setAddressCountry($recipientInfo['country']);
                $recipient->setReturnAddressId(1);
                $recipient->setSchedule(0);

                return $recipient;
            });
    }

    public function send(array $supporterInfo): void
    {
        $pdfHelper = app(PdfHelper::class);
        $this->supporterDirectoryForSending = $this->createDirectoryForSupporter($supporterInfo['Supporter ID']);

        // Create back pdf
        $pdfHelper->createPostcardBack($this->supporterDirectoryForSending, $this->getPostcardBackHtml());

        // Copy given front PDF to current supporter files
        $postcardFrontPdfPath = $this->getPostcardFrontPdfPath($supporterInfo['Postcard Image']);
        File::put($this->supporterDirectoryForSending .'/postcard_front.pdf', File::get($postcardFrontPdfPath));

        $postcardSendHelper = new PostcardSendHelper;
        $postcardSendHelper->print($supporterInfo, $this->createRecipients());

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
