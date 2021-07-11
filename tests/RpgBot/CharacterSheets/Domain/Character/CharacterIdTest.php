<?php

declare(strict_types=1);

namespace Tests\RpgBot\CharacterSheets\Domain\Character;

use Ramsey\Uuid\UuidInterface;
use RpgBot\CharacterSheets\Domain\Character\CharacterId;
use PHPUnit\Framework\TestCase;

class CharacterIdTest extends TestCase
{
    public function testCreate(): void
    {
        $id = CharacterId::generate();

        $this->assertInstanceOf(CharacterId::class, $id);
    }

    public function testCreateFromString(): void
    {
        $id = CharacterId::generate();
        $uuidObj = CharacterId::fromString($id->toString());

        $this->assertInstanceOf(CharacterId::class, $uuidObj);
    }

    public function testDomainException(): void
    {
        $this->expectException(\DomainException::class);
        
        CharacterId::fromString('some_string_but_no_uuid');
    }
}
