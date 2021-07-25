<?php

declare(strict_types=1);

namespace Tests\RpgBot\CharacterSheets\Domain\Character;

use RpgBot\CharacterSheets\Domain\Character\Character;
use PHPUnit\Framework\TestCase;
use RpgBot\CharacterSheets\Domain\Character\CharacterId;
use RpgBot\CharacterSheets\Domain\Character\Exception\InvalidExperienceException;
use RpgBot\CharacterSheets\Domain\Character\Exception\InvalidNameException;
use RpgBot\CharacterSheets\Domain\Character\Exception\InvalidWorkspaceException;

class CharacterTest extends TestCase
{
    public function testCharacterCreation(): void
    {
        $characterId = CharacterId::generate();
        $name = 'oswald';
        $workspace = 'slackspace';

        $character = Character::create($characterId, $workspace, $name);

        $this->assertSame($characterId->toString(), $character->getCharacterId());
        $this->assertSame($workspace, $character->getWorkspace());
        $this->assertSame($name, $character->getName());
        $this->assertSame(1, $character->getLevel());
    }

    public function testCharacterProgressionWithExp(): void
    {
        $characterId = CharacterId::generate();
        $name = 'oswald';
        $workspace = 'slackspace';

        $character = Character::create($characterId, $workspace, $name, 0);
        $this->assertSame(1, $character->getLevel());

        $character = Character::create($characterId, $workspace, $name, 49);
        $this->assertSame(1, $character->getLevel());

        $character = Character::create($characterId, $workspace, $name, 50);
        $this->assertSame(2, $character->getLevel());

        $character = Character::create($characterId, $workspace, $name, 51);
        $this->assertSame(2, $character->getLevel());

        $character = Character::create($characterId, $workspace, $name, 150);
        $this->assertSame(3, $character->getLevel());

        $character = Character::create($characterId, $workspace, $name, 5000);
        $this->assertSame(14, $character->getLevel());
    }

    public function testCharacterWorkspaceEmptyException(): void
    {
        $this->expectException(InvalidWorkspaceException::class);
        Character::create(CharacterId::generate(), '', 'name', 5);
    }

    public function testCharacterNameEmptyException(): void
    {
        $this->expectException(InvalidNameException::class);
        Character::create(CharacterId::generate(), 'xy', '', 5);
    }

    public function testInvalidExperienceException(): void
    {
        $this->expectException(InvalidExperienceException::class);
        Character::create(CharacterId::generate(), 'xy', 'test', -5);
    }
}
