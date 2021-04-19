<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\SendCampaign;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCampaign;
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
            ->set('participantsUpload', $file)
            ->call('upload')
            ->assertSee('The file was successfully uploaded.');

        Storage::disk('uploads')->assertExists(now()->format('Y-m-d__H-i-s').'_participants.csv');
    }

    /** @test **/
    public function it_shows_campaigns_from_config_in_select(): void
    {
        Livewire::test(SendCampaign::class)
            ->assertDontSee('OilCampaign');

        config()->set('postcards.campaigns', [
            ['name' => 'Test Campaign', 'class' =>TestCampaign::class]
        ]);

        Livewire::test(SendCampaign::class)
            ->assertSee('Test Campaign');
    }

    /** @test **/
    public function it_sends_the_campaign_and_trigger_pdf_print_calls_to_clicksend(): void
    {
        Http::fake();

        // Arrange and Act
        Livewire::test(SendCampaign::class)
            ->set('participantsUploadFilePath', base_path('tests/dummy-participants.csv'))
            ->set('campaignClass', TestCampaign::class)
            ->call('send');

    	// Assert
        Http::assertSentCount(2);
    }

}
