<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\SimpleExcel\SimpleExcelReader;
use Storage;

class SendCampaign extends Component
{
    use WithFileUploads;

    public $supportersUpload;

    public $supportersUploadFilePath;

    public $campaignClass;

    public function render(): View
    {
        return view('livewire.send-campaign');
    }

    public function upload(): void
    {
        $fileName = $this->supportersUpload->storeAs('/', now()->format('Y-m-d__H-i-s') . '_supporters.csv', 'uploads');
        $this->supportersUploadFilePath = Storage::disk('uploads')->path($fileName);

        session()->flash('message', 'The file was successfully uploaded.');
    }

    public function send(): void
    {
        $campaign = new $this->campaignClass;

        SimpleExcelReader::create($this->supportersUploadFilePath)->getRows()
            ->each(function (array $supporterInfo) use ($campaign) {
                $campaign->send($supporterInfo);
            });

        // Hook to define custom actions that should run after every sent postcard
        $campaign->postSendHook();
    }
}
