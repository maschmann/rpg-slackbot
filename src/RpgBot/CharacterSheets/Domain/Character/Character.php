<?php

declare(strict_types=1);

namespace RpgBot\CharacterSheets\Domain\Character;

use RpgBot\CharacterSheets\Domain\Character\Contract\BasePropertyInterface;
use RpgBot\CharacterSheets\Domain\Character\Exception\InvalidExperienceException;
use RpgBot\CharacterSheets\Domain\Character\Exception\InvalidNameException;
use RpgBot\CharacterSheets\Domain\Character\Exception\InvalidWorkspaceException;

/**
 * Aggregate root
 */
class Character
{
    private const MIN_EXPERIENCE = 0;

    /**
     * Character constructor.
     *
     * @param CharacterId $characterId
     * @param string $workspace
     * @param string $name
     * @param string $slackId
     * @param int $experience
     * @param BasePropertyInterface[] $skills
     * @param BasePropertyInterface[] $achievements
     * @param BasePropertyInterface[] $attributes
     */
    private function __construct(
        private CharacterId $characterId,
        private string $workspace,
        private string $name,
        private string $slackId,
        private int $experience = 0,
        private array $skills = [],
        private array $achievements = [],
        private array $attributes = [],
    ) {
    }

    /**
     * @param CharacterId $characterId
     * @param string $workspace
     * @param string $name
     * @param int $experience
     * @param BasePropertyInterface[] $skills
     * @param BasePropertyInterface[] $achievements
     * @param BasePropertyInterface[] $attributes
     * @return self
     */
    public static function create(
        CharacterId $characterId,
        string $workspace,
        string $name,
        string $slackId,
        int $experience = 0,
        array $skills = [],
        array $achievements = [],
        array $attributes = [],
    ): self {
        if ('' === $workspace) {
            throw new InvalidWorkspaceException("A workspace cannot be empty");
        }

        // if name is already taken, we should check later
        if ('' === $name) {
            throw new InvalidNameException("A character needs a name");
        }

        if (self::MIN_EXPERIENCE > $experience) {
            throw new InvalidExperienceException(
                sprintf("The minimum experience is %s", self::MIN_EXPERIENCE)
            );
        }

        return new self($characterId, $workspace, $name, $slackId, $experience, $skills, $achievements, $attributes);
    }

    public function getCharacterId(): string
    {
        return $this->characterId->toString();
    }

    public function getWorkspace(): string
    {
        return $this->workspace;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSlackId(): string
    {
        return $this->slackId;
    }

    public function getLevel(): int
    {
        // arithmetic levelling progression, based on quadratic equation
        return (1 + (int)floor((sqrt(625 + 100 * $this->experience) - 25) / 50));
    }

    public function getExperience(): int
    {
        return $this->experience;
    }

    /**
     * @return BasePropertyInterface[]
     */
    public function getSkills(): array
    {
        return $this->skills;
    }

    /**
     * @return BasePropertyInterface[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @return BasePropertyInterface[]
     */
    public function getAchievements(): array
    {
        return $this->achievements;
    }
}
