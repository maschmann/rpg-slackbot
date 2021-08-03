<?php

declare(strict_types=1);

namespace App\Controller;

use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Psr\Log\LoggerInterface;
use RpgBot\CharacterSheets\Application\Command\CharacterSheet\CharacterSheetCreationCommand;
use RpgBot\CharacterSheets\Application\Query\CharacterSheetQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use RpgBot\CharacterSheets\Domain\Character\Character;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('api')]
class CharacterController
{
    public function __construct(
        private CharacterSheetQuery $sheetQuery,
        private MessageBusInterface $commandBus,
        private SerializerInterface $serializer,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns a list of characters"
     * )
     * @OA\Tag(name="characters")
     * @Security(name="Bearer")
     */
    #[Route('/characters', name: 'character_list', methods: ['POST'])]
    public function list(Request $request): Response
    {
        $this->logger->debug(print_r($request->request->get('text'), true));
        $characterList = $this->sheetQuery->getAll();

        $characters = [];
        foreach ($characterList as $item) {
            $characters[] = $item->getName() . " " . $item->getLevel();
        }

        if (0 === count($characters)) {
            $characters[] = "No characters found";
        }

        //@todo think of actually adding templates and rendering here, even though it's just text
        return new Response(
            implode("\n", $characters) . " " . $request->request->get('text')
        );
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
     * @OA\Tag(name="character")
     * @Security(name="Bearer")
     */
    #[Route('/character', name: 'character_by_name', methods: ['POST'])]
    public function byName(string $name): JsonResponse
    {
        // \<\@(?P<user_id>[a-zA-Z]+)\|(?P<user_name>.+)\>(?P<args>.+)

        $user = $this->sheetQuery->getByName($name);
        if (empty($user)) {
            return new JsonResponse(
                ["status" => 'User not found']
            );
        }

        return new JsonResponse(
            [
                'data' => $this->serializer->serialize($user, 'array')
            ]
        );
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
