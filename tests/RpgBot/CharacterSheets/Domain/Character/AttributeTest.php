<?php

declare(strict_types=1);

namespace Tests\RpgBot\CharacterSheets\Domain\Character;

use RpgBot\CharacterSheets\Domain\Character\Attribute;
use PHPUnit\Framework\TestCase;
use RpgBot\CharacterSheets\Domain\Character\Exception\InvalidLevelException;

class AttributeTest extends TestCase
{
    public function testCreateAttribute(): void
    {
        $name = 'strength';
        $level = 3;

        $attribute = Attribute::create($name, $level);

        $this->assertSame($name, $attribute->getName());
        $this->assertSame($level, $attribute->getLevel());
    }

    public function testAttributeLowerLimitException(): void
    {
        $this->expectException(InvalidLevelException::class);
        Attribute::create('other', -1);
    }

    public function testAttributeUpperLimitException(): void
    {
        $this->expectException(InvalidLevelException::class);
        Attribute::create('test', 100);
    }
}
