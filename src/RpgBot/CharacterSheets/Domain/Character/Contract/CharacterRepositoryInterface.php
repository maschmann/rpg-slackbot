<?php

declare(strict_types=1);

namespace RpgBot\CharacterSheets\Domain\Character\Contract;

use RpgBot\CharacterSheets\Domain\Character\Character;

interface CharacterRepositoryInterface
{
    public function create(Character $character): void;
    public function store(Character $character): void;
    public function storeProperty(Character $character, BasePropertyInterface $property): void;
    public function addProperty(Character $character, BasePropertyInterface $property): void;
    public function delete(Character $character): void;
    public function getBySlackId(string $slackId): ?Character;

    /**
     * @return Character[]
     */
    public function getAll(): array;
}
