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

    protected $rules = [
        'supportersUpload' => 'required|mimes:csv,txt',
    ];

    protected $messages = [
        'supportersUpload.required' => 'You must provide a file with supporters.',
        'supportersUpload.mimes' => 'The supporters file must be of type CSV.',
    ];


    public function render(): View
    {
        return view('livewire.send-campaign');
    }

    public function storeUploadedSupporters(): void
    {
        $fileName = $this->supportersUpload->storeAs('/', now()->format('Y-m-d__H-i-s') . '_supporters.csv', 'uploads');
        $this->supportersUploadFilePath = Storage::disk('uploads')->path($fileName);
    }

    public function send(): void
    {
        $this->validate();
        if(!$this->supportersUploadFilePath) {
            $this->storeUploadedSupporters();
        }

        $campaign = new $this->campaignClass;
        SimpleExcelReader::create($this->supportersUploadFilePath)->getRows()
            ->each(function (array $supporterInfo) use ($campaign) {
                $campaign->send($supporterInfo);
            });

        session()->flash('message', 'Campaign successfully triggered.');

        // Hook to define custom actions that should run after every sent postcard
        $campaign->postSendHook();
    }
}
