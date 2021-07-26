<?php

declare(strict_types=1);

namespace RpgBot\CharacterSheets\Domain\Character;

use RpgBot\CharacterSheets\Domain\Character\Contract\CharacterRepositoryInterface;
use RpgBot\CharacterSheets\Domain\Character\Exception\CharacterNotFoundException;
use RpgBot\CharacterSheets\Domain\Character\Exception\InvalidNameException;

class CharacterSheetService
{
    public function __construct(
        private CharacterRepositoryInterface $characterRepository
    ) {
    }

    public function create(Character $character): void
    {
        $this->checkIfNameIsAlreadyTaken($character->getName());
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

    private function checkIfNameIsAlreadyTaken(string $name): void
    {
        if (null !== $this->characterRepository->getByName($name)) {
            throw new InvalidNameException(
                sprintf("The name %s is already taken!", $name)
            );
        };
    }
}
