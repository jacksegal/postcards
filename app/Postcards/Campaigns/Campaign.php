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

    protected string $campaignDirectory = '';

    protected array $supporterInfo;

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
        $this->supporterInfo = $supporterInfo;

        dispatch(new OrderPostcardsUsingSupporter($supporterInfo, $this));
    }

    public function postSendHook(): void
    {

    }

    public function getPostcardFrontHtml(array $supporterInfo): string
    {
        return '';
    }

    public function getPostcardBackHtml(): string
    {
        return view('pdf.default.back', ['message' => $this->supporterInfo['Message']])->render();
    }

    public function createPostcardFrontPdf(string $supporterCampaignDirectory): string
    {
        switch ($this->supporterInfo['Postcard Image']) {
            case 'image-1':
                $frontPdfFilename = 'postcard-front-bycatch.pdf';
                break;
            case 'image-2':
                $frontPdfFilename = 'postcard-front-climate-strike.pdf';
                break;
            case 'image-3':
                $frontPdfFilename = 'postcard-front-deep-sea-mining.pdf';
                break;
            default:
                $frontPdfFilename = 'postcard-front-bycatch.pdf';
        }

        File::put(Storage::disk('campaigns')->path($supporterCampaignDirectory .'/postcard_front.pdf'), file_get_contents(asset('pdfs/static/ban-fossil-fuel-advertisements/'.$frontPdfFilename)));

        return Storage::disk('campaigns')->url($supporterCampaignDirectory .'/postcard_front.pdf');
    }

    public function createPostcardBackPdf(string $supporterCampaignDirectory): string
    {
        $html = $this->getPostcardBackHtml();
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

    public function getSupporterDirectoryName(): string
    {
        return $this->supporterInfo['Supporter ID'] . '-' . time() . '-' .  Str::random(3);
    }

    public function createDirectoryForSupporter(): string
    {
        $supporterDirectoryName = $this->getSupporterDirectoryName();
        Storage::disk('campaigns')->makeDirectory($this->getCampaignDirectoryName() . '/' . $supporterDirectoryName);

        return $this->campaignDirectory . '/' . $supporterDirectoryName;
    }

}
