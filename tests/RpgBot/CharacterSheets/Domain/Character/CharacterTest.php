<?php

declare(strict_types=1);

namespace Tests\RpgBot\CharacterSheets\Domain\Character;

use RpgBot\CharacterSheets\Domain\Character\Character;
use PHPUnit\Framework\TestCase;
use RpgBot\CharacterSheets\Domain\Character\CharacterId;
use RpgBot\CharacterSheets\Domain\Character\Exception\InvalidExperienceException;
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

    public function testCharacterProgressionWithExp(): void
    {
        $characterId = CharacterId::generate();
        $name = 'oswald';

        $character = Character::create($characterId, $name, 0);
        $this->assertSame(1, $character->getLevel());

        $character = Character::create($characterId, $name, 49);
        $this->assertSame(1, $character->getLevel());

        $character = Character::create($characterId, $name, 50);
        $this->assertSame(2, $character->getLevel());

        $character = Character::create($characterId, $name, 51);
        $this->assertSame(2, $character->getLevel());

        $character = Character::create($characterId, $name, 150);
        $this->assertSame(3, $character->getLevel());

        $character = Character::create($characterId, $name, 5000);
        $this->assertSame(14, $character->getLevel());
    }

    public function testCharacterNameEmptyException(): void
    {
        $this->expectException(InvalidNameException::class);
        Character::create(CharacterId::generate(), '');
    }

    public function testInvalidExperienceException(): void
    {
        $this->expectException(InvalidExperienceException::class);
        Character::create(CharacterId::generate(), 'test', -5);
    }
}
