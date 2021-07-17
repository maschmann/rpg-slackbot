<?php

declare(strict_types=1);

namespace RpgBot\CharacterSheets\Infrastructure;

use RpgBot\CharacterSheets\Domain\Character\Character;
use RpgBot\CharacterSheets\Domain\Character\CharacterRepositoryInterface;
use Doctrine\DBAL\Connection;

class DbalCharacterSheetRepository implements CharacterRepositoryInterface
{

    public function __construct(
        private Connection $connection
    ) {
    }

    public function store(Character $character): void
    {
        $stmt = $this->connection->prepare('
            INSERT INTO appointments (id, start_time, length, pet_name, owner_name, contact_number)
            VALUES (:id, :start_time, :length, :pet_name, :owner_name, :contact_number) 
        ');

        $stmt->bindValue('id', $appointment->getId()->toString());
        $stmt->bindValue('start_time', $appointment->getStartTime()->format(self::DATE_FORMAT));
        $stmt->bindValue('length', $appointment->getLengthInMinutes());
        $stmt->bindValue('pet_name', $appointment->getPet()->getName());
        $stmt->bindValue('owner_name', $appointment->getPet()->getOwnerName());
        $stmt->bindValue('contact_number', $appointment->getPet()->getContactNumber());

        $stmt->executeQuery();
    }

    public function delete(Character $character): void
    {
        // TODO: Implement delete() method.
    }

    public function getByName(string $name): ?Character
    {
        // TODO: Implement getByName() method.
    }

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        // TODO: Implement getAll() method.
    }
}
