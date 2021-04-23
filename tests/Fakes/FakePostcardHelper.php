<?php


namespace Tests\Fakes;

use Illuminate\Support\Collection;
use Tests\TestCase;

class FakePostcardHelper
{
    private array $sentPostcards = [];

    public function send(array $supporterInfo, Collection $recipients, array $postcardCoverPaths): void
    {
        $this->sentPostcards[] = [$supporterInfo, $recipients, $postcardCoverPaths];
    }

    public function assertPostcardSent(array $supporterInfo, Collection $recipients, array $postcardCoverPaths): void
    {
        TestCase::assertEquals([$supporterInfo, $recipients, $postcardCoverPaths], $this->sentPostcards[0]);
    }
}
