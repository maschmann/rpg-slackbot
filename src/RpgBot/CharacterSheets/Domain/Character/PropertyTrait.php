<?php

declare(strict_types=1);

namespace RpgBot\CharacterSheets\Domain\Character;

trait PropertyTrait
{
    /**
     * @OA\Property(type="string")
     */
    private string $name;

    /**
     * @OA\Property(type="int")
     */
    private int $level;

    private function __construct(string $name, int $level)
    {
        $this->name = $name;
        $this->level = $level;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLevel(): int
    {
        return $this->level;
    }
}
