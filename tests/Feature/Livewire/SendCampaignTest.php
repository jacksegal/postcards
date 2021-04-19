<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\SendCampaign;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
            ->set('supportersUpload', $file)
            ->call('upload')
            ->assertSee('The file was successfully uploaded.');

        Storage::disk('uploads')->assertExists(now()->format('Y-m-d__H-i-s').'_supporters.csv');
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
    public function it_stores_PDFs_for_every_supporter(): void
    {
        // Arrange
        Carbon::setTestNow(now());
        Http::fake();
        $campaignDirectoryName = now()->format('Y-m-d__H-i-s').'_'.Str::of(TestCampaign::class)->afterLast('\\')->snake();

        // Act
        Livewire::test(SendCampaign::class)
            ->set('supportersUploadFilePath', base_path('tests/dummy-supporters.csv'))
            ->set('campaignClass', TestCampaign::class)
            ->call('send');

        // Assert
        $this->assertDirectoryExists(Storage::disk('campaigns')->path($campaignDirectoryName));
        $this->assertDirectoryExists(Storage::disk('campaigns')->path($campaignDirectoryName.'/194764356'));
        $this->assertDirectoryExists(Storage::disk('campaigns')->path($campaignDirectoryName.'/194764357'));
        $this->assertFileExists(Storage::disk('campaigns')->path($campaignDirectoryName.'/194764356/postcard_back.pdf'));
        $this->assertFileExists(Storage::disk('campaigns')->path($campaignDirectoryName.'/194764356/postcard_front.pdf'));
    }

    /** @test **/
    public function it_sends_the_campaign_and_triggers_pdf_print_calls_to_clicksend(): void
    {
        Http::fake();

        // Arrange and Act
        Livewire::test(SendCampaign::class)
            ->set('supportersUploadFilePath', base_path('tests/dummy-supporters.csv'))
            ->set('campaignClass', TestCampaign::class)
            ->call('send');

    	// Assert
        Http::assertSentCount(2);
    }

}
