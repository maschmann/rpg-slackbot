<?php

declare(strict_types=1);

namespace App\Controller;

use App\Slack\Infrastructure\SlackCall;
use Psr\Log\LoggerInterface;
use RpgBot\CharacterSheets\Application\Command\CharacterSheet\CharacterSheetCreationCommand;
use RpgBot\CharacterSheets\Application\Query\CharacterSheetQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Taking the easy road here, since the focus is on DDD and returning nicely rendered strings to
 * slack is totally nuts without nice templates.
 * We're not really working with an API approach anymore, since I'd expect it to be REST or GraphQL for
 * slack. But as it seems, slack wants nicely formatted strings plus some HTTP code.
 */
#[Route('api/characters')]
class CharacterController extends AbstractController
{
    public function __construct(
        private CharacterSheetQuery $sheetQuery,
        private MessageBusInterface $commandBus,
        private LoggerInterface $logger,
        private SlackCall $slackCall,
    ) {
    }

    #[Route('/list', name: 'characters_list', methods: ['POST'])]
    public function list(Request $request): Response
    {
        $this->logger->debug(print_r($request->request->get('text'), true));
        $characterList = $this->sheetQuery->getAll();

        return $this->render(
            'characters/list.html.twig',
            [
                'characters' => $characterList,
            ]
        );
    }

    #[Route('/show', name: 'characters_by_slack_id', methods: ['POST'])]
    public function byId(Request $request): Response
    {
        $requestData = $this->slackCall->extractCallData($request->request->all());
        $character = $this->sheetQuery->getBySlackId($requestData->getId());

        return $this->render(
            'characters/character.html.twig',
            [
                'character' => $character,
            ]
        );
    }

    #[Route('/create', name: 'characters_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $requestData = $this->slackCall->extractCallData($request->request->all());

        $this->commandBus->dispatch(
            new CharacterSheetCreationCommand(
                $requestData->getId(),
                $requestData->getUserName()
            )
        );

        return $this->render(
            'characters/create.html.twig',
            [
                'name' => $requestData->getUserName(),
            ]
        );
    }
}
