<?php

declare(strict_types=1);

namespace App\Controller;

use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/characters')]
class CharacterController
{
    public function __construct(
        private MessageBusInterface $messageBus
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
    #[Route('', name: 'character_list', methods: ['GET', 'HEAD'])]
    public function list(): JsonResponse
    {
        return new JsonResponse();
    }

    #[Route('/{name}', name: 'character_by_name', requirements: ['page' => '\w+'], methods: ['GET', 'HEAD'])]
    public function byName(string $name): JsonResponse
    {
        return new JsonResponse();
    }
}
