<?php

declare(strict_types=1);

namespace App\Slack\Dto;

use App\Slack\Dto\Contract\EventDtoInterface;

class HandshakeEventDto implements EventDtoInterface
{
    private function __construct(
        private string $challenge,
    ) {
    }

    /**
     * @param array<string,string> $handshake
     * @return EventDtoInterface
     */
    public static function create(array $handshake): EventDtoInterface
    {
        return new self($handshake['challenge']);
    }

    /**
     * @return string
     */
    public function getChallenge(): string
    {
        return $this->challenge;
    }
}
