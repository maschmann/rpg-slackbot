<?php

declare(strict_types=1);

namespace RpgBot\CharacterSheets\Domain\Character;

interface CharacterRepositoryInterface
{
    public function store(Character $character): void;
    public function delete(Character $character): void;
    public function getByName(string $name): ?Character;

    /**
     * @return Character[]
     */
    public function getAll(): array;
}
