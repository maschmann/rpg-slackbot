<?php

declare(strict_types=1);

namespace RpgBot\CharacterSheets\Application\Command\CharacterSheet;

use RpgBot\CharacterSheets\Application\Command\Contract\CommandInterface;
use RpgBot\CharacterSheets\Application\Command\Contract\HandlerInterface;
use RpgBot\CharacterSheets\Domain\Character\Character;
use RpgBot\CharacterSheets\Domain\Character\CharacterSheetService;
use RpgBot\CharacterSheets\Domain\Character\Exception\UserAlreadyExistsException;

class CharacterSheetCreationHandler implements HandlerInterface
{
    public function __construct(
        private CharacterSheetService $sheetService
    ) {
    }

    public function __invoke(CommandInterface $command): void
    {
        $character = Character::create(
            $command->getId(),
            $command->getName()
        );

        try {
            try {
                $this->sheetService->create($character);
            } catch (UserAlreadyExistsException $exception) {
                throw new \RuntimeException($exception->getMessage());
            }
        } catch (\DomainException $exception) { // maybe add specific handling later
            throw new \RuntimeException("The character could not be created");
        }
    }
}
