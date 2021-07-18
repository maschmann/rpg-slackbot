<?php

declare(strict_types=1);

namespace RpgBot\CharacterSheets\Infrastructure;

use RpgBot\CharacterSheets\Domain\Character\CharacterId;
use RpgBot\CharacterSheets\Domain\Character\Contract\BasePropertyInterface;
use RpgBot\CharacterSheets\Domain\Character\Character;
use RpgBot\CharacterSheets\Domain\Character\Contract\CharacterRepositoryInterface;
use Doctrine\DBAL\Connection;
use RpgBot\CharacterSheets\Domain\Character\Exception\CharacterNotFoundException;

class DbalCharacterSheetRepository implements CharacterRepositoryInterface
{

    public function __construct(
        private Connection $connection
    ) {
    }

    public function store(Character $character): void
    {
        /*$stmt = $this->connection->prepare('
            INSERT INTO characters (id, name, level, experience)
            VALUES (:id, :name, :level, :experience)
        ');

        $stmt->bindValue('id', $character->getId()->toString());
        $stmt->bindValue('name', $character->getName());
        $stmt->bindValue('level', $character->getLevel());
        $stmt->bindValue('experience', $character->getExperience());

        $stmt->executeQuery();
        */

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

            }
        }
    }

    public function delete(Character $character): void
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param string $name
     * @return Character|null
     * @throws \Doctrine\DBAL\Exception
     */
    public function getByName(string $name): ?Character
    {
        $characterRaw = $this->connection->fetchAssociative(
            'SELECT id, name, level, experience FROM characters WHERE name = ?',
            [
                'name' => $name,
            ]
        );

        if (false === $characterRaw) {
            throw new CharacterNotFoundException(
                sprintf("The character with name %s could not be found", $name)
            );
        }

        // @TODO add character properties in a sensible manner
        $character = Character::create(
            CharacterId::fromString($characterRaw['id']),
            $characterRaw['name'],
            $characterRaw['level'],
            $characterRaw['experience']
        );

        return $character;
    }

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        // TODO: Implement getAll() method.
    }

    public function create(Character $character): void
    {
        $this->connection->insert(
            'characters',
            [
                'id' => $character->getCharacterId(),
                'name' => $character->getName(),
                'level' => $character->getLevel(),
                'experience' => $character->getExperience(),
            ]
        );
    }

    public function addProperty(Character $character, BasePropertyInterface $property): void
    {
        // TODO: Implement addProperty() method.
    }
}
