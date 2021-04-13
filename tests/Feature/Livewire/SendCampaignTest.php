<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\SendCampaign;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class SendCampaignTest extends TestCase
{

    /** @test **/
    public function it_uploads_csv_file_to_uploads_storage(): void
    {
        Carbon::setTestNow(now());

        Storage::fake('uploads');

        $file = UploadedFile::fake()->create('participants.csv');

        Livewire::test(SendCampaign::class)
            ->set('participants', $file)
            ->call('upload');

        Storage::disk('uploads')->assertExists(now()->format('Y-m-d__H-i-s').'_participants.csv');
    }

}
