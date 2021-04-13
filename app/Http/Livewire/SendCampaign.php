<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class SendCampaign extends Component
{
    use WithFileUploads;

    public $participants;

    public function render(): View
    {
        return view('livewire.send-campaign');
    }

    public function upload(): void
    {
        $this->participants->storeAs('/', now()->format('Y-m-d__H-i-s').'_participants.csv', 'uploads');
    }
}
