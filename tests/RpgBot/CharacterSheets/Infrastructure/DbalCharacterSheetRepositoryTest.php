<?php

declare(strict_types=1);

namespace Tests\RpgBot\CharacterSheets\Infrastructure;

use Doctrine\DBAL\Connection;
use RpgBot\CharacterSheets\Domain\Character\Character;
use RpgBot\CharacterSheets\Infrastructure\DbalCharacterSheetRepository;
use PHPUnit\Framework\TestCase;

class DbalCharacterSheetRepositoryTest extends TestCase
{
    public function testCanStoreCharacter(): void
    {
        $connection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $connection->expects($this->once())
            ->method('update');

        $characterId = 'XXXXXXX';
        $name = 'oswald';

        $character = Character::create($characterId, $name);

        $sheetRepository = new DbalCharacterSheetRepository($connection);
        $sheetRepository->store($character);
    }
}
