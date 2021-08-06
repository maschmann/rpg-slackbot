<?php

declare(strict_types=1);

namespace RpgBot\CharacterSheets\Domain\Character;

use RpgBot\CharacterSheets\Domain\Character\Contract\CharacterRepositoryInterface;
use RpgBot\CharacterSheets\Domain\Character\Exception\UserAlreadyExistsException;

class CharacterSheetService
{
    public function __construct(
        private CharacterRepositoryInterface $characterRepository
    ) {
    }

    public function create(Character $character): void
    {
        $this->checkIfUserAlreadyExists($character);
        $this->characterRepository->create($character);
    }

    public function store(Character $character): void
    {
        $this->characterRepository->store($character);
    }

    /**
     * @return Character[]
     */
    public function getAll(): array
    {
        return $this->characterRepository->getAll();
    }

    private function checkIfUserAlreadyExists(Character $character): void
    {
        if (null !== $this->characterRepository->getBySlackId($character->getCharacterId())) {
            throw new UserAlreadyExistsException(
                sprintf(
                    "The user with name %s and id %s already exists!",
                    $character->getName(),
                    $character->getCharacterId()
                )
            );
        };
    }
}
