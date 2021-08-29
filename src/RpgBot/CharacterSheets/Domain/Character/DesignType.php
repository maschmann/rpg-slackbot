<?php

declare(strict_types=1);

namespace RpgBot\CharacterSheets\Domain\Character;

use RpgBot\CharacterSheets\Domain\Character\Exception\InvalidNameException;

final class DesignType
{
    private function __construct(
        private string $name,
        private string $description,
    ) {
    }

    public static function create(string $name, string $description): self
    {
        if ('' === $name) {
            throw new InvalidNameException("The name cannot be empty");
        }

        return new self($name, $description);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }
}
