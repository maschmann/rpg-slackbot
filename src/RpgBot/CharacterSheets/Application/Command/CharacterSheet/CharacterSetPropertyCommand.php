<?php

declare(strict_types=1);

namespace RpgBot\CharacterSheets\Application\Command\CharacterSheet;

use RpgBot\CharacterSheets\Application\Command\Contract\CommandInterface;

class CharacterSetPropertyCommand implements CommandInterface
{
    public function __construct(
        private string $name,
        private string $type,
        private string $property,
        private string $level,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getProperty(): string
    {
        return $this->property;
    }

    public function getLevel(): string
    {
        return $this->level;
    }
}
