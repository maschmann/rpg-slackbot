<?php

declare(strict_types=1);

namespace RpgBot\CharacterSheets\Domain\Character\Contract;

interface BasePropertyInterface
{
    public static function create(string $name, int $level = 0): BasePropertyInterface;
    public function name(): string;
    public function level(): int;
}
