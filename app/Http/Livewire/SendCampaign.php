<?php

namespace App\Http\Livewire;

use App\Postcards\PostcardSendHelper;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\SimpleExcel\SimpleExcelReader;
use Storage;

class SendCampaign extends Component
{
    use WithFileUploads;

    public $participantsUpload;

    public $participantsUploadFilePath;

    public $campaignClass;

    public function render(): View
    {
        return view('livewire.send-campaign');
    }

    public function upload(): void
    {
        $fileName = $this->participantsUpload->storeAs('/', now()->format('Y-m-d__H-i-s').'_participants.csv', 'uploads');
        $this->participantsUploadFilePath = Storage::disk('uploads')->path($fileName);

        session()->flash('message', 'The file was successfully uploaded.');
    }

    public function send(): void
    {
        SimpleExcelReader::create($this->participantsUploadFilePath)->getRows()
            ->each(function(array $rowProperties) {
               $postcardSendHelper = new PostcardSendHelper;
               $postcardSendHelper->print();
            });
    }
}
