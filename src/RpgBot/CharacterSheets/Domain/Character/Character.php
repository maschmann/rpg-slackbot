<?php

declare(strict_types=1);

namespace RpgBot\CharacterSheets\Domain\Character;

use RpgBot\CharacterSheets\Domain\Character\Exception\InvalidNameException;

/**
 * Aggregate root
 */
class Character
{
    private CharacterId $characterId;

    private string $name;

    private int $level;

    private int $experience;

    /**
     * @var Achievement[]
     */
    private array $achievements;

    /**
     * @var Attribute[]
     */
    private array $attributes;

    /**
     * @var Skill[]
     */
    private array $skills;

    /**
     * @param CharacterId $characterId
     * @param string $name
     * @param int $experience
     * @param Skill[] $skills
     * @param Achievement[] $achievements
     * @param Attribute[] $attributes
     */
    private function __construct(
        CharacterId $characterId,
        string $name,
        int $experience = 0,
        array $skills = [],
        array $achievements = [],
        array $attributes = [],
    ) {
        $this->characterId = $characterId;
        $this->name = $name;
        $this->experience = $experience;
        $this->skills = $skills;
        $this->attributes = $attributes;
        $this->achievements = $achievements;
    }

    /**
     * @param CharacterId $characterId
     * @param string $name
     * @param int $experience
     * @param Skill[] $skills
     * @param Achievement[] $achievements
     * @param Attribute[] $attributes
     * @return self
     */
    public static function create(
        CharacterId $characterId,
        string $name,
        int $experience = 0,
        array $skills = [],
        array $achievements = [],
        array $attributes = [],
    ): self {
        // if name is already taken, we should check later
        if ('' === $name) {
            throw new InvalidNameException("A character needs a name");
        }

        // experience will translate to level
        $intialLevel = 1;

        return new self($characterId, $name, $experience, $skills, $achievements, $attributes);
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
