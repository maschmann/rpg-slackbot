<?php

declare(strict_types=1);

namespace RpgBot\CharacterSheets\Application\Query\Dto;

use RpgBot\CharacterSheets\Application\Query\Contract\PropertyDtoInterface;

class AchievementDto implements PropertyDtoInterface
{
    public function __construct(
        private string $name,
        private int $level
    ) {
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
