<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\SendCampaign;
use App\Postcards\PostcardSendHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\Fakes\FakePostcardHelper;
use Tests\TestCampaign;
use Tests\TestCampaignWithFileDeletion;
use Tests\TestCase;

class SendCampaignTest extends TestCase
{

    /** @test * */
    public function it_uploads_csv_file_to_uploads_storage(): void
    {
        Carbon::setTestNow(now());

        Storage::fake('uploads');

        $file = UploadedFile::fake()->create('participants.csv');

        Livewire::test(SendCampaign::class)
            ->set('supportersUpload', $file)
            ->call('upload')
            ->assertSee('The file was successfully uploaded.');

        Storage::disk('uploads')->assertExists(now()->format('Y-m-d__H-i-s') . '_supporters.csv');
    }

    /** @test * */
    public function it_shows_campaigns_from_config_in_select(): void
    {
        Livewire::test(SendCampaign::class)
            ->assertDontSee('OilCampaign');

        config()->set('postcards.campaigns', [
            ['name' => 'Test Campaign', 'class' => TestCampaign::class]
        ]);

        Livewire::test(SendCampaign::class)
            ->assertSee('Test Campaign');
    }

    /** @test * */
    public function it_keeps_supporter_pdfs_after_sent(): void
    {
        // Arrange
        Carbon::setTestNow(now());
        Http::fake();
        $campaignDirectoryName = now()->format('Y-m-d__H-i-s') . '_' . Str::of(TestCampaign::class)->afterLast('\\')->snake();

        // Act
        Livewire::test(SendCampaign::class)
            ->set('supportersUploadFilePath', base_path('tests/dummy-supporters.csv'))
            ->set('campaignClass', TestCampaign::class)
            ->call('send');

        // Assert supporter files are being stored
        $this->assertDirectoryExists(Storage::disk('campaigns')->path($campaignDirectoryName));
        $this->assertDirectoryExists(Storage::disk('campaigns')->path($campaignDirectoryName . '/194764356'));
        $this->assertDirectoryExists(Storage::disk('campaigns')->path($campaignDirectoryName . '/194764357'));
        $this->assertFileExists(Storage::disk('campaigns')->path($campaignDirectoryName . '/194764356/postcard_back.pdf'));
        $this->assertFileExists(Storage::disk('campaigns')->path($campaignDirectoryName . '/194764356/postcard_front.pdf'));
    }

    /** @test * */
    public function it_deletes_supporter_pdfs_after_sent(): void
    {
        // Arrange
        Carbon::setTestNow(now());
        Http::fake();
        $campaignDirectoryName = now()->format('Y-m-d__H-i-s') . '_' . Str::of(TestCampaign::class)->afterLast('\\')->snake();

        // Act
        Livewire::test(SendCampaign::class)
            ->set('supportersUploadFilePath', base_path('tests/dummy-supporters.csv'))
            ->set('campaignClass', TestCampaignWithFileDeletion::class)
            ->call('send');

        // Assert supporter files are being stored
        $this->assertDirectoryDoesNotExist(Storage::disk('campaigns')->path($campaignDirectoryName));
        $this->assertDirectoryDoesNotExist(Storage::disk('campaigns')->path($campaignDirectoryName . '/194764356'));
        $this->assertDirectoryDoesNotExist(Storage::disk('campaigns')->path($campaignDirectoryName . '/194764357'));
        $this->assertFileDoesNotExist(Storage::disk('campaigns')->path($campaignDirectoryName . '/194764356/postcard_back.pdf'));
        $this->assertFileDoesNotExist(Storage::disk('campaigns')->path($campaignDirectoryName . '/194764356/postcard_front.pdf'));
    }

    /** @test * */
    public function it_calls_postcard_send_helper_with_correct_arguments(): void
    {
        // Arrange
        $this->travelTo(Carbon::createFromFormat('Y-m-d h:i A', '2021-01-01 00:00 PM'),);
        $this->app->instance(PostcardSendHelper::class, new FakePostcardHelper());

        // Act
        Livewire::test(SendCampaign::class)
            ->set('supportersUploadFilePath', base_path('tests/dummy-supporters.csv'))
            ->set('campaignClass', TestCampaign::class)
            ->call('send');

        $fakePostcardHelper = app(PostcardSendHelper::class);

        $fakePostcardHelper->assertPostcardSent(
            $this->getSupporterInfo(),
            (new TestCampaign)->createRecipients(),
            $this->getPostcardCoverUrls()
        );

    }

    private function getSupporterInfo(): array
    {
        return [
            'Supporter ID' => '194764356', 'Supporter Email' => 'jack@c6digital.io', 'Date Sent' => '05/04/2021 04:01', 'Subject' => 'Ban fossil fuel ads!', 'Message' => '"Dear Ms von der Leyen,

Please ban fossil fuel ads now!

Thank you,
Jack Segal"', 'Salutation' => 'Dear Ms von der Leyen', 'Organization' => 'President of the European Commission', 'Position Held' => '', 'Contact Title' => 'Ms', 'Contact First Name' => 'Ursula', 'Contact Last Name' => 'von der Leyen', 'Supporter Title' => '', 'Supporter First Name' => 'Jack', 'Supporter Last Name' => 'Segal', 'Supporter Address 1' => '148 Rogate House', 'Supporter Address 2' => 'London', 'Supporter City' => '', 'Supporter Region' => '', 'Supporter Postal Code' => 'E5 8QX', 'Supporter Country' => '', 'Postcard Image' => 'postcard-front-bycatch'
        ];
    }

    private function getPostcardCoverUrls(): array
    {
        return [
            Storage::disk('campaigns')->url('2021-01-01__12-00-00_test_campaign/194764356/postcard_front.pdf'),
            Storage::disk('campaigns')->url('2021-01-01__12-00-00_test_campaign/194764356/postcard_back.pdf'),
        ];
    }

}
