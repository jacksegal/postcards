<?php

namespace App\Jobs;

use App\Postcards\PdfHelper;
use App\Postcards\PostcardSendHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class OrderPostcardsUsingSupporter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $supporterInfo;
    public $campaign;

    public function __construct(array $supporterInfo, $campaign){
        $this->campaign = $campaign;
        $this->supporterInfo = $supporterInfo;
    }

    public function handle(): void
    {
        $pdfHelper = app(PdfHelper::class);
        $supporterCampaignDirectory = $this->campaign->createDirectoryForSupporter($this->supporterInfo['Supporter ID']);

        // Create back pdf from message
        $postcardBackPdfUrl = $pdfHelper->createPostcardBack($supporterCampaignDirectory, $this->campaign->getPostcardBackHtml($this->supporterInfo));

        // Get front pdf by supporter info
        $postcardFrontPdfUrl = $pdfHelper->getPostcardFront($supporterCampaignDirectory, $this->supporterInfo['Postcard Image']);

        $postcardSendHelper = app(PostcardSendHelper::class);
        $postcardSendHelper->send($this->campaign->createRecipients(), [$postcardFrontPdfUrl, $postcardBackPdfUrl]);
    }
}
