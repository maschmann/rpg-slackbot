<?php

declare(strict_types=1);

namespace RpgBot\CharacterSheets\Domain\Character;

class CharacterSheetService
{
    public function __construct(
        private CharacterRepositoryInterface $characterRepository
    ) {
    }
}
