<?php

declare(strict_types=1);

namespace RpgBot\CharacterSheets\Application\Command\CharacterSheet;

use RpgBot\CharacterSheets\Application\Command\Contract\CommandInterface;

class CharacterSheetCreationCommand implements CommandInterface
{
    public function __construct(
        private string $workspace,
        private string $name,
        private string $slackId,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getWorkspace(): string
    {
        return $this->workspace;
    }

    /**
     * @return string
     */
    public function getSlackId(): string
    {
        return $this->slackId;
    }
}
