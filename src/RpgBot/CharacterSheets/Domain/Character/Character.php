<?php

declare(strict_types=1);

namespace RpgBot\CharacterSheets\Domain\Character;

use RpgBot\CharacterSheets\Domain\Character\Exception\InvalidNameException;

/**
 * Aggregate root
 */
class Character
{
    use CheckLevelTrait;

    private const MAX_LEVEL = 99;
    private const MIN_LEVEL = 0;

    private string $name;

    private int $level;

    private int $experience;

    private CharacterId $characterId;

    private function __construct(
        CharacterId $characterId,
        string $name,
        int $level = 0,
    ) {
        $this->characterId = $characterId;
        $this->name = $name;
        $this->level = $level;
    }

    public static function create(
        CharacterId $characterId,
        string $name,
        int $level = 0,
    ): self {
        // if name is already taken, we should check later
        if ('' === $name) {
            throw new InvalidNameException("A character needs a name");
        }

        self::checkLevel($level);

        return new self($characterId, $name);
    }

    public function getCharacterId(): string
    {
        return $this->characterId->toString();
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
