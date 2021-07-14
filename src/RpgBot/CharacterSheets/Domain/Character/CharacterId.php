<?php

declare(strict_types=1);

namespace RpgBot\CharacterSheets\Domain\Character;

use Ramsey\Uuid\Uuid;
use OpenApi\Annotations as OA;

class CharacterId
{
    /**
     * @OA\Property(type="string")
     */
    private string $id;

    private function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public static function fromString(string $id): self
    {
        if (false === Uuid::isValid($id)) {
            throw new \DomainException(
                \sprintf("CharacterId '%s' is not valid", $id)
            );
        }

        return new self($id);
    }

    public function toString(): string
    {
        return $this->id;
    }
}
