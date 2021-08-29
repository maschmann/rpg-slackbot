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

    public function getBySlackId(string $slackId): ?CharacterDto
    {
        $character = $this->repository->getBySlackId($slackId);
        if ($character) {
            return new CharacterDto(
                $character->name(),
                $character->level(),
                $character->experience(),
                $this->convertProperties($character->skills(), SkillDto::class),
                $this->convertProperties($character->achievements(), AchievementDto::class),
                $this->convertProperties($character->attributes(), AttributeDto::class),
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
                $item->name(),
                $item->level(),
                $item->experience(),
                $this->convertProperties($item->skills(), SkillDto::class),
                $this->convertProperties($item->achievements(), AchievementDto::class),
                $this->convertProperties($item->attributes(), AttributeDto::class),
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
                    $item->name(),
                    $item->level(),
                );
            },
            $properties
        );
    }
}
