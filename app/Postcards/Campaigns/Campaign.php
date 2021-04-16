<?php

namespace App\Postcards\Campaigns;

abstract class Campaign
{
    public string $message = '';

    public function getPdfBackHtml(): string
    {
        return view('pdf.template-default-back', ['message' => $this->message])->render();
    }

}
