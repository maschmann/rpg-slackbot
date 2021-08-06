<?php

declare(strict_types=1);

namespace RpgBot\CharacterSheets\Application\Command\CharacterSheet;

use RpgBot\CharacterSheets\Application\Command\Contract\CommandInterface;

class CharacterSheetCreationCommand implements CommandInterface
{
    public function __construct(
        private string $userId,
        private string $name,
    ) {
    }

    public function getId(): string
    {
        return $this->userId;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
