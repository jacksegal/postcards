<?php


namespace Tests\Fakes;

use Illuminate\Support\Collection;
use Tests\TestCase;

class FakePostcardHelper
{
    private array $sentPostcards = [];

    public function send(Collection $recipients, array $postcardCoverPaths): void
    {
        $this->sentPostcards[] = [$recipients, $postcardCoverPaths];
    }

    public function assertPostcardSent(Collection $recipients, array $postcardCoverPaths): void
    {
        TestCase::assertEquals([$recipients, $postcardCoverPaths], $this->sentPostcards[0]);
    }
}
