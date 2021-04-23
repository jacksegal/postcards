<?php


namespace App\Postcards;

use ClickSend\Api\PostPostcardApi;
use ClickSend\Model\PostPostcard;
use Exception;
use Illuminate\Support\Collection;

class PostcardSendHelper
{

    public function send(array $supporterInfo, Collection $recipients, array $postcardCoverUrls): void
    {
        $apiInstance = app(PostPostcardApi::class);
        $PostPostcard = new PostPostcard();

        // Front and back cover
        $PostPostcard->setFileUrls([$postcardCoverUrls[0], $postcardCoverUrls[1]]);

        // Send it
        $PostPostcard->setRecipients($recipients->toArray());

        try {
            $response = $apiInstance->postPostcardsSendPost($PostPostcard);

            info('response '. (string)$response);
        } catch (Exception $e) {
            info('Exception when calling PostPostcardApi->postPostcardsSendPost: ' . $e->getMessage() . PHP_EOL);
        }

    }
}
