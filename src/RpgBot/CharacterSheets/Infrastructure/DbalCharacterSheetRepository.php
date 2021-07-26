<?php

declare(strict_types=1);

namespace RpgBot\CharacterSheets\Infrastructure;

use RpgBot\CharacterSheets\Domain\Character\Achievement;
use RpgBot\CharacterSheets\Domain\Character\Attribute;
use RpgBot\CharacterSheets\Domain\Character\CharacterId;
use RpgBot\CharacterSheets\Domain\Character\Contract\BasePropertyInterface;
use RpgBot\CharacterSheets\Domain\Character\Character;
use RpgBot\CharacterSheets\Domain\Character\Contract\CharacterRepositoryInterface;
use Doctrine\DBAL\Connection;
use RpgBot\CharacterSheets\Domain\Character\Exception\CharacterNotFoundException;
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
            $character->getSkills(),
            $character->getAchievements(),
            $character->getAttributes(),
        );

        $this->connection->update(
            'characters',
            [
                'level' => $character->getLevel(),
                'experience' => $character->getExperience(),
            ],
            [
                'id' => $character->getCharacterId(),
            ]
        );

        if (count($properties) > 0) {
            foreach ($properties as $property) {
                $this->connection->update(
                    'properties',
                    [
                        'level' => $property->getLevel(),
                    ],
                    [
                        'id' => $character->getCharacterId(),
                        'name' => $property->getName(),
                        'type' => $property::class,
                    ]
                );
            }
        }
    }

    public function delete(Character $character): void
    {
        $this->connection->delete(
            'characters',
            [
                'name' => $character->getName(),
            ]
        );
    }

    /**
     * @param string $name
     * @return Character|null
     * @throws \Doctrine\DBAL\Exception
     */
    public function getByName(string $name): ?Character
    {
        $skills = [];
        $achievements = [];
        $attributes = [];

        $characterRaw = $this->connection->fetchAssociative(
            'SELECT id, workspace, name, experience FROM characters WHERE name = ?',
            [
                'name' => $name,
            ]
        );

        if (false === $characterRaw) {
            return null;
        }

        $properties =  $this->connection->fetchAssociative(
            'SELECT name, level, type FROM properties WHERE id = ?',
            [
                'id' => $characterRaw['id'],
            ]
        );

        if (false !== $properties) {
            foreach ($properties as $property) {
                switch ($property['type']) {
                    case Attribute::class:
                        $attributes[] = Attribute::create(
                            $property['name'],
                            $property['level']
                        );
                        break;
                    case Skill::class:
                        $attributes[] = Skill::create(
                            $property['name'],
                            $property['level']
                        );
                        break;
                    case Achievement::class:
                        $attributes[] = Achievement::create(
                            $property['name'],
                            $property['level']
                        );
                        break;
                }
            }
        }

        // @TODO add character properties in a sensible manner
        $character = Character::create(
            CharacterId::fromString($characterRaw['id']),
            $characterRaw['workspace'],
            $characterRaw['name'],
            $characterRaw['experience'],
            $skills,
            $achievements,
            $attributes
        );

        return $character;
    }

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        $characterList = [];

        $characterListRaw = $this->connection->fetchAssociative(
            'SELECT id, name, level, experience FROM characters'
        );

        if (false !== $characterListRaw) {
            $characterList = \array_map(
                function (array $row) {
                    return Character::create(
                        CharacterId::fromString($row['id']),
                        $row['workspace'],
                        $row['name'],
                        $row['level'],
                        $row['experience']
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
                'id' => $character->getCharacterId(),
                'workspace' => $character->getWorkspace(),
                'name' => $character->getName(),
                'level' => $character->getLevel(),
                'experience' => $character->getExperience(),
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
