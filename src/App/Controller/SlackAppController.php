<?php

declare(strict_types=1);

namespace App\Controller;

use App\Slack\Dto\HandshakeEventDto;
use App\Slack\Dto\UserEventDto;
use App\Slack\Infrastructure\Exception\InvalidActionException;
use App\Slack\Infrastructure\Exception\InvalidTypeException;
use App\Slack\Infrastructure\Slack;
use JoliCode\Slack\Exception\SlackErrorResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Psr\Log\LoggerInterface;
use RpgBot\CharacterSheets\Application\Query\CharacterSheetQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('api/slack')]
class SlackAppController
{
    public function __construct(
        private LoggerInterface $logger,
        private Slack $slack,
        private CharacterSheetQuery $characterSheetQuery,
        private MessageBusInterface $commandBus,
        private SerializerInterface $serializer,
    ) {
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="general slack receive url",
     * )
     * @OA\Tag(name="slack")
     * @Security(name="Bearer")
     */
    #[Route('/receive', name: 'slack_receive', methods: ['POST'])]
    public function receive(Request $request): JsonResponse
    {
        $client = $this->slack->client();
        $status = 200;
        $body = (string)$request->getContent();
        $this->logger->debug($body);
        $payload = [];

        $eventDto = $this->slack->handleEvent($body, $client);

        if (is_a($eventDto, HandshakeEventDto::class)) {
            return new JsonResponse(
                [
                    'challenge' => $eventDto->getChallenge(),
                ]
            );
        }

        if (is_a($eventDto, UserEventDto::class)) {
            // Explicit handling, not too much information will be given to the outside.
            try {
                try {
                    // no default action needed, will throw an exception
                    switch ($eventDto->getAction()) {
                        case Slack::ACTION_LIST_CHARACTERS:
                            $characterList = $this->characterSheetQuery->getAll();
                            break;
                        case Slack::ACTION_GET_CHARACTER:
                            $character = $this->characterSheetQuery->getByName($eventDto->getUserName());
                            if (null === $character) {
                                $payload['message'] = "You don't have a character yet";
                            }
                            break;
                    }
                } catch (InvalidActionException $actionException) {
                    $payload['exception'] = $actionException->getMessage();
                    $status = 422;
                }
            } catch (InvalidTypeException $invalidTypeException) {
                $payload['exception'] = $invalidTypeException->getMessage();
                $status = 422;
            }
        }

        return new JsonResponse($payload, $status);
    }
}
