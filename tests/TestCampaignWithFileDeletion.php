<?php


namespace Tests;


use App\Postcards\Campaigns\Campaign;
use Illuminate\Support\Facades\Storage;

class TestCampaignWithFileDeletion extends Campaign
{
    private string $name = 'Test Campaign';

    public function getName(): string
    {
        return $this->name;
    }

    public function postSendHook(): void
    {
        Storage::disk('campaigns')->deleteDirectory($this->getCampaignDirectoryName());
    }
}
