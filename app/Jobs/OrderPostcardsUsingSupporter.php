<?php

namespace App\Jobs;

use App\Postcards\PostcardSendHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class OrderPostcardsUsingSupporter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $supporterInfo;
    public $campaign;
    public $tries = 1;

    public function __construct(array $supporterInfo, $campaign){
        $this->campaign = $campaign;
        $this->supporterInfo = $supporterInfo;
    }

    public function handle(): void
    {
        $supporterCampaignDirectory = $this->campaign->createDirectoryForSupporter($this->supporterInfo);

        // Create front pdf for postcard
        $postcardFrontPdfUrl = $this->campaign->createPostcardFrontPdf($supporterCampaignDirectory, $this->supporterInfo);

        // Create back pdf for postcard
        $postcardBackPdfUrl = $this->campaign->createPostcardBackPdf($supporterCampaignDirectory, $this->supporterInfo);

        $postcardSendHelper = app(PostcardSendHelper::class);
        $postcardSendHelper->send($this->campaign->createRecipients($this->supporterInfo), [$postcardFrontPdfUrl, $postcardBackPdfUrl]);
    }
}
