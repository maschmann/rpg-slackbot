<?php

declare(strict_types=1);

namespace RpgBot\CharacterSheets\Domain\Character;

use RpgBot\CharacterSheets\Domain\Character\Exception\InvalidNameException;

class PropertyAbstract
{
    use CheckLevelTrait;

    private const MAX_LEVEL = 99;
    private const MIN_LEVEL = 0;

    private string $name;

    private int $level;

    private function __construct(string $name, int $level)
    {
        $this->name = $name;
        $this->level = $level;
    }

    public static function create(string $name, int $level = 0): self
    {
        if ('' === $name) {
            throw new InvalidNameException("The name cannot be empty");
        }

        self::checkLevel($level);

        return new self($name, $level);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLevel(): int
    {
        return $this->level;
    }
}