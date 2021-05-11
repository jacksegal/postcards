<?php


namespace App\Postcards;

use App\Exceptions\ClickSendException;
use ClickSend\Api\PostPostcardApi;
use ClickSend\Model\PostPostcard;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class PostcardSendHelper
{

    public function send(Collection $recipients, array $postcardCoverUrls): void
    {
        $apiInstance = app(PostPostcardApi::class);
        $PostPostcard = new PostPostcard();

        // Front and back cover
        $PostPostcard->setFileUrls([$postcardCoverUrls[0], $postcardCoverUrls[1]]);

        // Send it
        $PostPostcard->setRecipients($recipients->toArray());

        try {
            $response = $apiInstance->postPostcardsSendPost($PostPostcard);
            collect(json_decode($response)->data->recipients)->each(function (object $recipient) {
                if ($recipient->status !== 'SUCCESS') {
                    Log::error('ClickSend Error:' . $recipient->status . PHP_EOL);
                    throw new ClickSendException('Recipient Error: ' . $recipient->status);
                }
            });

            Log::info('ClickSend Success: response ' . (string)$response);
        } catch (Exception $e) {
            Log::error('ClickSendError: ' . $e->getMessage() . PHP_EOL);
            throw new ClickSendException($e->getMessage());
        }

    }
}
