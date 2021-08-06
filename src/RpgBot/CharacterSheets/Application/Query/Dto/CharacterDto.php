<?php

declare(strict_types=1);

namespace RpgBot\CharacterSheets\Application\Query\Dto;

use RpgBot\CharacterSheets\Application\Query\Contract\PropertyDtoInterface;

class CharacterDto
{
    /**
     * @param string $name
     * @param int $level
     * @param int $experience
     * @param PropertyDtoInterface[] $skills
     * @param PropertyDtoInterface[] $achievements
     * @param PropertyDtoInterface[] $attributes
     */
    public function __construct(
        private string $name,
        private int $level,
        private int $experience,
        private array $skills,
        private array $achievements,
        private array $attributes,
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

    public function getExperience(): int
    {
        return $this->experience;
    }

    /**
     * @return PropertyDtoInterface[]
     */
    public function getSkills(): array
    {
        return $this->skills;
    }

    /**
     * @return PropertyDtoInterface[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @return PropertyDtoInterface[]
     */
    public function getAchievements(): array
    {
        return $this->achievements;
    }
}
