<?php

declare(strict_types=1);

namespace Tests\RpgBot\CharacterSheets\Domain\Character;

use RpgBot\CharacterSheets\Domain\Character\Character;
use PHPUnit\Framework\TestCase;
use RpgBot\CharacterSheets\Domain\Character\CharacterId;
use RpgBot\CharacterSheets\Domain\Character\Exception\InvalidLevelException;
use RpgBot\CharacterSheets\Domain\Character\Exception\InvalidNameException;

class CharacterTest extends TestCase
{
    public function testCharacterCreation(): void
    {
        $characterId = CharacterId::generate();
        $name = 'oswald';

        $character = Character::create($characterId, $name);

        $this->assertSame($characterId->toString(), $character->getCharacterId());
        $this->assertSame($name, $character->getName());
        $this->assertSame(1, $character->getLevel());
    }

    public function testCharacterNameEmptyException(): void
    {
        $this->expectException(InvalidNameException::class);
        Character::create(CharacterId::generate(), '');
    }

    public function testInvalidLevelException(): void
    {
        $this->expectException(InvalidLevelException::class);
        Character::create(CharacterId::generate(), 'test', -5);
    }
}
