<?php

namespace App\Postcards\Campaigns;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class GreenHomes extends Campaign
{

    public function getRecipients(array $supporterInfo = []): array
    {
        return [
            [
                'name' => $this->getMpNameFromSupporterInfo($supporterInfo),
                'address_line_1' => 'House of Commons',
                'address_line_2' => '',
                'city' => 'London',
                'state' => 'London',
                'zip' => 'SW1A 0AA',
                'country' => 'GB',
                'return_address_id' => 99727,
                'schedule' => 0,
            ]
        ];
    }

    public function getPostcardBackHtml(): string
    {
        return view('pdf.default.back', ['message' => $this->supporterInfo['Message']])->render();
    }

    public function createPostcardFrontPdf(string $supporterCampaignDirectory): string
    {
        switch ($this->supporterInfo['Subject']) {
            case 'Please tell Rishi Sunak: block green investment and we all pay':
                $url = 'https://storage.googleapis.com/gpuk/campaigns/green-homes-postcards/postcard-thermal.pdf';
                break;
            case 'Please tell Rishi Sunak not to block climate action':
                $url = 'https://storage.googleapis.com/gpuk/campaigns/green-homes-postcards/postcard-boiler-man.pdf';
                break;
            case 'Please speak up for green investment':
                $url = 'https://storage.googleapis.com/gpuk/campaigns/green-homes-postcards/postcard-code-red-montage.pdf';
                break;
            case 'Please speak up for climate action':
                $url = 'https://storage.googleapis.com/gpuk/campaigns/green-homes-postcards/postcard-heat-pump-to-help-out.pdf';
                break;
            case 'Speak up for green investment in this Spending Review':
                $url = 'https://storage.googleapis.com/gpuk/campaigns/green-homes-postcards/postcard-chart.pdf';
                break;
            case 'Tell Rishi Sunak: don’t block climate action':
                $url = 'https://storage.googleapis.com/gpuk/campaigns/green-homes-postcards/postcard-boilers-fires.pdf';
                break;
            default:
                $url = 'https://storage.googleapis.com/gpuk/campaigns/green-homes-postcards/postcard-thermal.pdf';
        }

        return $url;
    }

    public function postSendHook(): void
    {
        Storage::disk('campaigns')->deleteDirectory($this->getCampaignDirectoryName());
    }


    private function getMpNameFromSupporterInfo(array $supporterInfo = [])
    {
        return  $supporterInfo['Contact Title'] . ' ' . $supporterInfo['Contact First Name'] . ' ' . $supporterInfo['Contact Last Name'];
    }

}
