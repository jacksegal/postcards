<?php


namespace App\Postcards\Campaigns;


use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class TransformTransport extends Campaign
{

    public function getRecipients(array $supporterInfo = []): array
    {
        return [
            [
                'name' => 'Jack Segal',
                'address_line_1' => '148 Rogate House',
                'address_line_2' => '8 Muir Road',
                'city' => 'London',
                'state' => 'London',
                'zip' => 'E5 8QX',
                'country' => 'United Kingdom',
                'return_address_id' => 99727,
                'schedule' => 0,
            ]
        ];
    }

    public function getPostcardBackHtml(): string
    {
        return view('pdf.transform-transport.back', ['message' => $this->supporterInfo['Message']])->render();
    }

    public function createPostcardFrontPdf(string $supporterCampaignDirectory): string
    {
        switch ($this->supporterInfo['Postcard Image']) {
            case 'buses':
                $frontPdfFilename = 'postcards-final-buses.pdf';
                break;
            case 'cycling':
                $frontPdfFilename = 'postcards-final-cycling.pdf';
                break;
            case 'traffic':
                $frontPdfFilename = 'postcards-final-traffic.pdf';
                break;
            case 'trains':
                $frontPdfFilename = 'postcards-final-trains.pdf';
                break;
            case 'walking':
                $frontPdfFilename = 'postcards-final-walking.pdf';
                break;
            default:
                $frontPdfFilename = 'postcards-final-trains.pdf';
        }

        File::put(Storage::disk('campaigns')->path($supporterCampaignDirectory .'/postcard_front.pdf'), file_get_contents(asset('pdfs/static/transform-transport/'.$frontPdfFilename)));

        return Storage::disk('campaigns')->url($supporterCampaignDirectory .'/postcard_front.pdf');
    }

    public function postSendHook(): void
    {
        Storage::disk('campaigns')->deleteDirectory($this->getCampaignDirectoryName());
    }

}
