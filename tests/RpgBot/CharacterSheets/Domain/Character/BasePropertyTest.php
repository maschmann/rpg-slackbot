<?php

declare(strict_types=1);

namespace Tests\RpgBot\CharacterSheets\Domain\Character;

use RpgBot\CharacterSheets\Domain\Character\BaseProperty;
use PHPUnit\Framework\TestCase;
use RpgBot\CharacterSheets\Domain\Character\Exception\InvalidLevelException;

class BasePropertyTest extends TestCase
{
    public function testCreateBaseProperty(): void
    {
        $name = 'strength';
        $level = 3;

        $attribute = BaseProperty::create($name, $level);

        $this->assertSame($name, $attribute->name());
        $this->assertSame($level, $attribute->level());
    }

    public function testBasePropertyLowerLimitException(): void
    {
        $this->expectException(InvalidLevelException::class);
        BaseProperty::create('other', -1);
    }

    public function testBasePropertyUpperLimitException(): void
    {
        $this->expectException(InvalidLevelException::class);
        BaseProperty::create('test', 100);
    }
}
