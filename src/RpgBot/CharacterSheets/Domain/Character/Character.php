<?php

declare(strict_types=1);

namespace RpgBot\CharacterSheets\Domain\Character;

use RpgBot\CharacterSheets\Domain\Character\Contract\BasePropertyInterface;
use RpgBot\CharacterSheets\Domain\Character\Exception\InvalidExperienceException;
use RpgBot\CharacterSheets\Domain\Character\Exception\IdMissingException;
use RpgBot\CharacterSheets\Domain\Character\Exception\UserNameMissingException;

/**
 * Aggregate root
 */
final class Character
{
    private const MIN_EXPERIENCE = 0;

    /**
     * Character constructor.
     *
     * @param string $characterId
     * @param string $name
     * @param int $experience
     * @param BasePropertyInterface[] $skills
     * @param BasePropertyInterface[] $achievements
     * @param BasePropertyInterface[] $attributes
     */
    private function __construct(
        private string $characterId,
        private string $name,
        private int $experience = 0,
        private array $skills = [],
        private array $achievements = [],
        private array $attributes = [],
    ) {
    }

    /**
     * @param string $characterId
     * @param string $name
     * @param int $experience
     * @param BasePropertyInterface[] $skills
     * @param BasePropertyInterface[] $achievements
     * @param BasePropertyInterface[] $attributes
     * @return self
     */
    public static function create(
        string $characterId,
        string $name,
        int $experience = 0,
        array $skills = [],
        array $achievements = [],
        array $attributes = [],
    ): self {
        if ('' === $name) {
            throw new UserNameMissingException("A character needs a name");
        }

        if ('' === $characterId) {
            throw new IdMissingException("A character needs an id");
        }

        if (self::MIN_EXPERIENCE > $experience) {
            throw new InvalidExperienceException(
                sprintf("The minimum experience is %s", self::MIN_EXPERIENCE)
            );
        }

        return new self($characterId, $name, $experience, $skills, $achievements, $attributes);
    }

    public function characterId(): string
    {
        return $this->characterId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function level(): int
    {
        // arithmetic levelling progression, based on quadratic equation
        return (1 + (int)floor((sqrt(625 + 100 * $this->experience) - 25) / 50));
    }

    public function experience(): int
    {
        return $this->experience;
    }

    /**
     * @return BasePropertyInterface[]
     */
    public function skills(): array
    {
        return $this->skills;
    }

    /**
     * @return BasePropertyInterface[]
     */
    public function attributes(): array
    {
        return $this->attributes;
    }

    /**
     * @return BasePropertyInterface[]
     */
    public function achievements(): array
    {
        return $this->achievements;
    }
}
