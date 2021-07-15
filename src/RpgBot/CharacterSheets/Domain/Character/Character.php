<?php

declare(strict_types=1);

namespace RpgBot\CharacterSheets\Domain\Character;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

/**
 * Aggregate root
 */
class Character
{
    /**
     * @OA\Property(type="string")
     */
    private string $name;

    /**
     * @OA\Property(ref=@Model(type=CharacterId::class))
     */
    private CharacterId $characterId;

    private function __construct(
        CharacterId $characterId,
    ) {
        $this->characterId = $characterId;
    }
}
