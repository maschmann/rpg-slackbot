<?php

declare(strict_types=1);

namespace RpgBot\CharacterSheets\Application\Query\Contract;

interface PropertyDtoInterface
{
    public function getName(): string;

    public function getLevel(): int;
}
