<?php


namespace App\Postcards;


use Illuminate\Support\Facades\Http;

class PostcardSendHelper
{

    public function print()
    {
        Http::post('click-and-send');
    }
}
