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
                $recipient->setReturnAddressId(1);
                $recipient->setSchedule(0);

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

    public function getPostcardBackHtml(string $message): string
    {
        return view('pdf.template-default-back', ['message' => $message])->render();
    }

    public function getCampaignDirectoryName(): string
    {
        return $this->campaignDirectory = now()->format('Y-m-d__H-i-s') . '_' . Str::of(TestCampaign::class)->afterLast('\\')->snake();
    }

    public function createDirectoryForSupporter(string $supporterId): string
    {
        Storage::disk('campaigns')->makeDirectory($this->getCampaignDirectoryName() . '/' . $supporterId);

        return $this->campaignDirectory . '/' . $supporterId;
    }

}
