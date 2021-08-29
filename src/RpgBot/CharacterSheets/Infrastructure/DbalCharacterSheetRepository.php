<?php

declare(strict_types=1);

namespace RpgBot\CharacterSheets\Infrastructure;

use RpgBot\CharacterSheets\Domain\Character\Achievement;
use RpgBot\CharacterSheets\Domain\Character\Attribute;
use RpgBot\CharacterSheets\Domain\Character\Contract\BasePropertyInterface;
use RpgBot\CharacterSheets\Domain\Character\Character;
use RpgBot\CharacterSheets\Domain\Character\Contract\CharacterRepositoryInterface;
use Doctrine\DBAL\Connection;
use RpgBot\CharacterSheets\Domain\Character\Skill;

class DbalCharacterSheetRepository implements CharacterRepositoryInterface
{

    public function __construct(
        private Connection $connection
    ) {
    }

    public function store(Character $character): void
    {
        $properties = array_merge(
            $character->skills(),
            $character->achievements(),
            $character->attributes(),
        );

        $this->connection->update(
            'characters',
            [
                'experience' => $character->experience(),
            ],
            [
                'id' => $character->characterId(),
            ]
        );

        if (count($properties) > 0) {
            foreach ($properties as $property) {
                $this->connection->update(
                    'properties',
                    [
                        'level' => $property->level(),
                    ],
                    [
                        'id' => $character->characterId(),
                        'name' => $property->name(),
                        'type' => $property::class,
                    ]
                );
            }
        }
    }

    public function delete(Character $character): void
    {
        $this->connection->delete(
            'properties',
            [
                'character_id' => $character->characterId(),
            ]
        );

        $this->connection->delete(
            'characters',
            [
                'id' => $character->characterId(),
            ]
        );
    }

    /**
     * @param string $slackId
     * @return Character|null
     * @throws \Doctrine\DBAL\Exception
     */
    public function getBySlackId(string $slackId): ?Character
    {
        $skills = [];
        $achievements = [];
        $attributes = [];

        $characterRaw = $this->connection->fetchAssociative(
            'SELECT id, name, experience FROM characters WHERE id = ?',
            [
                $slackId,
            ]
        );

        if (false === $characterRaw) {
            return null;
        }

        $properties =  $this->connection->fetchAssociative(
            'SELECT name, level, type FROM properties WHERE character_id = ?',
            [
                $characterRaw['id'],
            ]
        );

        if (false !== $properties) {
            foreach ($properties as $property) {
                switch ($property['type']) {
                    case Attribute::class:
                        $attributes[] = Attribute::create(
                            $property['name'],
                            (int)$property['level']
                        );
                        break;
                    case Skill::class:
                        $skills[] = Skill::create(
                            $property['name'],
                            (int)$property['level']
                        );
                        break;
                    case Achievement::class:
                        $achievements[] = Achievement::create(
                            $property['name'],
                            (int)$property['level']
                        );
                        break;
                }
            }
        }

        return Character::create(
            $characterRaw['id'],
            $characterRaw['name'],
            (int)$characterRaw['experience'],
            $skills,
            $achievements,
            $attributes
        );
    }

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        $characterList = [];

        $characterListRaw = $this->connection->fetchAllAssociative(
            'SELECT id, name, experience FROM characters'
        );

        if (false != $characterListRaw) {
            $characterList = \array_map(
                function (array $row) {
                    return Character::create(
                        $row['id'],
                        $row['name'],
                        (int)$row['experience']
                    );
                },
                $characterListRaw
            );
        }

        return $characterList;
    }

    public function create(Character $character): void
    {
        $this->connection->insert(
            'characters',
            [
                'id' => $character->characterId(),
                'name' => $character->name(),
                'experience' => $character->experience(),
            ]
        );
    }

    public function storeProperty(Character $character, BasePropertyInterface $property): void
    {
    }

    public function addProperty(Character $character, BasePropertyInterface $property): void
    {
        // TODO: Implement addProperty() method.
    }
}
