<?php

declare(strict_types=1);

namespace RpgBot\CharacterSheets\Domain\Character;

use RpgBot\CharacterSheets\Domain\Character\Exception\LevelInvalidException;

class Attribute
{
    use PropertyTrait;

    private const MAX_LEVEL = 99;
    private const MIN_LEVEL = 0;

    public static function create(string $name, int $level = 0): self
    {
        if (self::MAX_LEVEL < $level || self::MIN_LEVEL > $level) {
            throw new LevelInvalidException(
                sprintf("The minimum level is %s and the maximum level is %s", self::MIN_LEVEL, self::MAX_LEVEL)
            );
        }

        return new self($name, $level);
    }
}
