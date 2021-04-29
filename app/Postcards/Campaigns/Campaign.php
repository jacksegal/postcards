<?php

namespace App\Postcards\Campaigns;

use App\Jobs\OrderPostcardsUsingSupporter;
use ClickSend\Model\PostRecipient;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCampaign;

abstract class Campaign implements CampaignContract
{

    private string $campaignDirectory = '';

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

    public function getPostcardBackHtml(array $supporterInfo): string
    {
        return view('pdf.template-default-back', ['message' => $supporterInfo['Message']])->render();
    }

    public function getCampaignDirectoryName(): string
    {
        return $this->campaignDirectory = now()->format('Y-m-d__H-i-s') . '_' . Str::of(TestCampaign::class)->afterLast('\\')->snake();
    }

    public function getSupporterDirectoryName(array $supporterInfo): string
    {
        return $supporterInfo['Supporter ID'];
    }

    public function createDirectoryForSupporter(array $supporterInfo): string
    {
        $supporterDirectoryName = $this->getSupporterDirectoryName($supporterInfo);
        Storage::disk('campaigns')->makeDirectory($this->getCampaignDirectoryName() . '/' . $supporterDirectoryName);

        return $this->campaignDirectory . '/' . $supporterDirectoryName;
    }

}
