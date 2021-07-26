<?php

declare(strict_types=1);

namespace App\Controller;

use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use RpgBot\CharacterSheets\Application\Command\CharacterSheet\CharacterSheetCreationCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use RpgBot\CharacterSheets\Domain\Character\Character;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('api/characters')]
class CharacterController
{
    public function __construct(
        private MessageBusInterface $queryBus,
        private MessageBusInterface $commandBus,
        private SerializerInterface $serializer,
    ) {
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns a list of characters",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Character::class, groups={"full"}))
     *     )
     * )
     * @OA\Tag(name="characters")
     * @Security(name="Bearer")
     */
    #[Route('', name: 'character_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        return new JsonResponse();
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns a character",
     *     @OA\JsonContent()
     * )
     *
     * @OA\Parameter(
     *     name="name",
     *     in="query",
     *     description="The name of the character",
     *     @OA\Schema(type="string")
     * )
     * @OA\Tag(name="characters")
     * @Security(name="Bearer")
     */
    #[Route('/{name}', name: 'character_by_name', requirements: ['page' => '\w+'], methods: ['GET'])]
    public function byName(string $name): JsonResponse
    {
        return new JsonResponse();
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns a character",
     *     @OA\JsonContent()
     * )
     * @OA\Tag(name="characters")
     * @Security(name="Bearer")
     */
    #[Route(
        '/create/{workspace}/{name}',
        name: 'character_create',
        requirements: ['workspace' => '\w+', 'name' => '\w+'],
        methods: ['POST']
    )]
    public function create(string $workspace, string $name): JsonResponse
    {
        $this->commandBus->dispatch(
            new CharacterSheetCreationCommand($workspace, $name)
        );

        return new JsonResponse();
    }
}
