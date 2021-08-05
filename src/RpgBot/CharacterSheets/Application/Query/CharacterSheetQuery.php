<?php

declare(strict_types=1);

namespace RpgBot\CharacterSheets\Application\Query;

use RpgBot\CharacterSheets\Application\Query\Contract\PropertyDtoInterface;
use RpgBot\CharacterSheets\Application\Query\Dto\AchievementDto;
use RpgBot\CharacterSheets\Application\Query\Dto\AttributeDto;
use RpgBot\CharacterSheets\Application\Query\Dto\CharacterDto;
use RpgBot\CharacterSheets\Application\Query\Dto\SkillDto;
use RpgBot\CharacterSheets\Domain\Character\Character;
use RpgBot\CharacterSheets\Domain\Character\Contract\BasePropertyInterface;
use RpgBot\CharacterSheets\Domain\Character\Contract\CharacterRepositoryInterface;

class CharacterSheetQuery
{
    public function __construct(
        private CharacterRepositoryInterface $repository
    ) {
    }

    public function getByName(string $name): ?CharacterDto
    {
        $character = $this->repository->getByName($name);
        if ($character) {
            return new CharacterDto(
                $character->getName(),
                $character->getSlackId(),
                $character->getLevel(),
                $character->getExperience(),
                $this->convertProperties($character->getSkills(), SkillDto::class),
                $this->convertProperties($character->getAchievements(), AchievementDto::class),
                $this->convertProperties($character->getAttributes(), AttributeDto::class),
            );
        }

        return null;
    }

    /**
     * @return CharacterDto[]
     */
    public function getAll(): array
    {
        $results = $this->repository->getAll();
        return \array_map(function (Character $item) {
            return new CharacterDto(
                $item->getName(),
                $item->getSlackId(),
                $item->getLevel(),
                $item->getExperience(),
                $this->convertProperties($item->getSkills(), SkillDto::class),
                $this->convertProperties($item->getAchievements(), AchievementDto::class),
                $this->convertProperties($item->getAttributes(), AttributeDto::class),
            );
        }, $results);
    }

    /**
     * @param BasePropertyInterface[] $properties
     * @param string $class
     * @return PropertyDtoInterface[]
     */
    private function convertProperties(array $properties, string $class): array
    {
        return \array_map(
            function (BasePropertyInterface $item) use ($class) {
                return new $class(
                    $item->getName(),
                    $item->getLevel(),
                );
            },
            $properties
        );
    }
}
