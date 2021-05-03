<?php

namespace App\Postcards\Campaigns;

use App\Jobs\OrderPostcardsUsingSupporter;
use App\Postcards\PdfHelper;
use ClickSend\Model\PostRecipient;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCampaign;

abstract class Campaign implements CampaignContract
{

    private string $campaignDirectory = '';

    public function createRecipients(): Collection
    {

        return collect( $this->getRecipients())
            ->map(function(array $recipientInfo){
                $recipient = new PostRecipient();
                $recipient->setAddressName($recipientInfo['name']);
                $recipient->setAddressLine1($recipientInfo['address_line_1']);
                $recipient->setAddressLine2($recipientInfo['address_line_2']);
                $recipient->setaddressCity($recipientInfo['city']);
                $recipient->setaddressState($recipientInfo['state']);
                $recipient->setAddressPostalCode($recipientInfo['zip']);
                $recipient->setAddressCountry($recipientInfo['country']);
                $recipient->setReturnAddressId($recipientInfo['return_address_id']);
                $recipient->setSchedule($recipientInfo['schedule']);

                return $recipient;
            });
    }

    public function send(array $supporterInfo): void
    {
        dispatch(new OrderPostcardsUsingSupporter($supporterInfo, $this));
    }

    public function postSendHook(): void
    {

    }

    public function getPostcardFrontHtml(array $supporterInfo): string
    {
        return '';
    }

    public function getPostcardBackHtml(array $supporterInfo): string
    {
        return view('pdf.template-default-back', ['message' => $supporterInfo['Message']])->render();
    }

    public function createPostcardFrontPdf(string $supporterCampaignDirectory, string $html, array $supporterInfo): string
    {
        $pdfHelper = app(PdfHelper::class);

        if(empty($html)) {
            File::put(Storage::disk('campaigns')->path($supporterCampaignDirectory .'/postcard_front.pdf'), file_get_contents(asset('pdfs/static/ban-fossil-fuel-advertisements/'.$supporterInfo['Postcard Image'].'.pdf')));

            return Storage::disk('campaigns')->url($supporterCampaignDirectory .'/postcard_front.pdf');
        }

        $pdfHelper
            ->useHtml($html)
            ->outputPath(Storage::disk('campaigns')->path($supporterCampaignDirectory) . '/postcard_back.pdf')
            ->create();

        return Storage::disk('campaigns')->url($supporterCampaignDirectory . '/postcard_back.pdf');
    }

    public function createPostcardBackPdf(string $supporterCampaignDirectory, string $html, array $supporterInfo): string
    {
        $pdfHelper = app(PdfHelper::class);

        $pdfHelper
            ->useHtml($html)
            ->outputPath(Storage::disk('campaigns')->path($supporterCampaignDirectory) . '/postcard_back.pdf')
            ->create();

        return Storage::disk('campaigns')->url($supporterCampaignDirectory . '/postcard_back.pdf');
    }

    public function getCampaignDirectoryName(): string
    {
        return $this->campaignDirectory = now()->format('Y-m-d__H-i-s') . '_' . Str::of(TestCampaign::class)->afterLast('\\')->snake();
    }

    public function getSupporterDirectoryName(array $supporterInfo): string
    {
        return $supporterInfo['Supporter ID'] . '-' . time() . '-' .  Str::random(3);
    }

    public function createDirectoryForSupporter(array $supporterInfo): string
    {
        $supporterDirectoryName = $this->getSupporterDirectoryName($supporterInfo);
        Storage::disk('campaigns')->makeDirectory($this->getCampaignDirectoryName() . '/' . $supporterDirectoryName);

        return $this->campaignDirectory . '/' . $supporterDirectoryName;
    }

}
