<?php

declare(strict_types=1);

namespace App\Slack\Dto;

class CharacterCreationActionStateDto
{
    private function __construct(
        private string $characterClass,
    ) {
    }

    public static function create(
        string $characterClass
    ): self {
        // some validation will be needed.
        return new self(
            $characterClass
        );
    }

    public function characterClass(): string
    {
        return $this->characterClass;
    }
}
