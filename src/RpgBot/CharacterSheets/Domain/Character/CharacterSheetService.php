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
        $this->checkIfUserAlreadyExists($character->getSlackId(), $character->getName());
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

    private function checkIfUserAlreadyExists(string $slackId, string $name): void
    {
        if (null !== $this->characterRepository->getBySlackId($slackId)) {
            throw new UserAlreadyExistsException(
                sprintf("The user with name %s and id %s already exists!", $name, $slackId)
            );
        };
    }
}
