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

    private CharacterId $characterId;

    private string $workspace;

    private string $name;

    private int $experience;

    /**
     * @var BasePropertyInterface[]
     */
    private array $achievements;

    /**
     * @var BasePropertyInterface[]
     */
    private array $attributes;

    /**
     * @var BasePropertyInterface[]
     */
    private array $skills;

    /**
     * Character constructor.
     *
     * @param CharacterId $characterId
     * @param string $workspace
     * @param string $name
     * @param int $experience
     * @param BasePropertyInterface[] $skills
     * @param BasePropertyInterface[] $achievements
     * @param BasePropertyInterface[] $attributes
     */
    private function __construct(
        CharacterId $characterId,
        string $workspace,
        string $name,
        int $experience = 0,
        array $skills = [],
        array $achievements = [],
        array $attributes = [],
    ) {
        $this->characterId = $characterId;
        $this->workspace = $workspace;
        $this->name = $name;
        $this->experience = $experience;
        $this->skills = $skills;
        $this->attributes = $attributes;
        $this->achievements = $achievements;
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

        return new self($characterId, $workspace, $name, $experience, $skills, $achievements, $attributes);
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
     * @return Skill[]
     */
    public function getSkills(): array
    {
        return $this->skills;
    }

    /**
     * @return Attribute[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @return Achievement[]
     */
    public function getAchievements(): array
    {
        return $this->achievements;
    }
}
