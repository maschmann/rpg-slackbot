<?php

declare(strict_types=1);

namespace RpgBot\CharacterSheets\Domain\Character;

use RpgBot\CharacterSheets\Domain\Character\Exception\InvalidLevelException;

trait CheckLevelTrait
{
    private static function checkLevel(int $level): void
    {
        if (self::MAX_LEVEL < $level || self::MIN_LEVEL > $level) {
            throw new InvalidLevelException(
                sprintf("The minimum level is %s and the maximum level is %s", self::MIN_LEVEL, self::MAX_LEVEL)
            );
        }
    }
}
