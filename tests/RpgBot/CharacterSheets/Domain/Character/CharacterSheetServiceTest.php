<?php

declare(strict_types=1);

namespace Tests\RpgBot\CharacterSheets\Domain\Character;

use RpgBot\CharacterSheets\Domain\Character\Character;
use RpgBot\CharacterSheets\Domain\Character\CharacterId;
use RpgBot\CharacterSheets\Domain\Character\CharacterSheetService;
use PHPUnit\Framework\TestCase;
use RpgBot\CharacterSheets\Domain\Character\Contract\CharacterRepositoryInterface;
use RpgBot\CharacterSheets\Domain\Character\Exception\UserAlreadyExistsException;

class CharacterSheetServiceTest extends TestCase
{
    public function testCharacterCanBeStored(): void
    {
        $storage = [];
        $ourCharacter = Character::create(CharacterId::generate(), 'default', 'TheKing!', 'XXXXXXX');

        $repository = $this->getMockBuilder(CharacterRepositoryInterface::class)
            ->getMock();

        $repository->expects($this->once())
            ->method('store')
            ->willReturnCallback(
                function ($character) use (&$storage) {
                    $storage[] = $character;
                }
            );

        $service = new CharacterSheetService($repository);
        $service->store($ourCharacter);

        $this->assertSame($ourCharacter, array_pop($storage));
    }

    public function testCharacterCanBeCreated(): void
    {
        $storage = [];
        $ourCharacter = Character::create(CharacterId::generate(), 'default', 'TheKing!', 'XXXXXXX');

        $repository = $this->getMockBuilder(CharacterRepositoryInterface::class)
            ->getMock();

        $repository->expects($this->once())
            ->method('create')
            ->willReturnCallback(
                function ($character) use (&$storage) {
                    $storage[] = $character;
                }
            );

        $service = new CharacterSheetService($repository);
        $service->create($ourCharacter);

        $this->assertSame($ourCharacter, array_pop($storage));
    }

    public function testCharacterNameAlreadyTaken(): void
    {
        $this->expectException(UserAlreadyExistsException::class);
        $ourCharacter = Character::create(CharacterId::generate(), 'default', 'TheKing!', 'XXXXXXX');

        $repository = $this->getMockBuilder(CharacterRepositoryInterface::class)
            ->getMock();

        $repository->expects($this->never())
            ->method('create');

        $repository->expects($this->once())
            ->method('getBySlackId')
            ->willReturn($ourCharacter);

        $service = new CharacterSheetService($repository);
        $service->create($ourCharacter);
    }
}
