<?php

declare(strict_types=1);

namespace Tests\RpgBot\CharacterSheets\Domain\Character;

use RpgBot\CharacterSheets\Domain\Character\Character;
use PHPUnit\Framework\TestCase;
use RpgBot\CharacterSheets\Domain\Character\Exception\InvalidExperienceException;
use RpgBot\CharacterSheets\Domain\Character\Exception\IdMissingException;
use RpgBot\CharacterSheets\Domain\Character\Exception\UserNameMissingException;

class CharacterTest extends TestCase
{
    public function testCharacterCreation(): void
    {
        $characterId = 'XXXXXXX';
        $name = 'oswald';

        $character = Character::create($characterId, $name);

        $this->assertSame($characterId, $character->characterId());
        $this->assertSame($name, $character->name());
        $this->assertSame(1, $character->level());
    }

    public function testCharacterProgressionWithExp(): void
    {
        $characterId = 'XXXXXXX';
        $name = 'oswald';

        $character = Character::create($characterId, $name, 0);
        $this->assertSame(1, $character->level());

        $character = Character::create($characterId, $name, 49);
        $this->assertSame(1, $character->level());

        $character = Character::create($characterId, $name, 50);
        $this->assertSame(2, $character->level());

        $character = Character::create($characterId, $name, 51);
        $this->assertSame(2, $character->level());

        $character = Character::create($characterId, $name, 150);
        $this->assertSame(3, $character->level());

        $character = Character::create($characterId, $name, 5000);
        $this->assertSame(14, $character->level());
    }

    public function testCharacterHasNoIdException(): void
    {
        $this->expectException(IdMissingException::class);
        Character::create('', 'name', 5);
    }

    public function testCharacterNameEmptyException(): void
    {
        $this->expectException(UserNameMissingException::class);
        Character::create('XXXXXXX', '', 5);
    }

    public function testInvalidExperienceException(): void
    {
        $this->expectException(InvalidExperienceException::class);
        Character::create('XXXXXXX', 'test', -5);
    }
}
