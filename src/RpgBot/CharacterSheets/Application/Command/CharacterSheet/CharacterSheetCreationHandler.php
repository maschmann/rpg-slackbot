<?php

declare(strict_types=1);

namespace RpgBot\CharacterSheets\Application\Command\CharacterSheet;

use RpgBot\CharacterSheets\Application\Command\Contract\CommandInterface;
use RpgBot\CharacterSheets\Application\Command\Contract\HandlerInterface;
use RpgBot\CharacterSheets\Domain\Character\Character;
use RpgBot\CharacterSheets\Domain\Character\CharacterId;
use RpgBot\CharacterSheets\Domain\Character\CharacterSheetService;
use RpgBot\CharacterSheets\Domain\Character\Exception\InvalidNameException;

class CharacterSheetCreationHandler implements HandlerInterface
{
    public function __construct(
        private CharacterSheetService $sheetService
    ) {
    }

    public function __invoke(CommandInterface $command): void
    {
        $character = Character::create(
            CharacterId::generate(),
            $command->getWorkspace(),
            $command->getName(),
            $command->getSlackId(),
        );

        try {
            $this->sheetService->create($character);
        } catch (InvalidNameException $exception) {
            throw new \RuntimeException("The character could not be created");
        }
    }
}
