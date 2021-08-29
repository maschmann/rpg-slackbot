<?php

declare(strict_types=1);

namespace App\Controller;

use App\Slack\Infrastructure\SlackCall;
use App\Slack\Infrastructure\SlackEvent;
use App\Slack\Infrastructure\SlackFormView;
use Psr\Log\LoggerInterface;
use RpgBot\CharacterSheets\Application\Command\CharacterSheet\CharacterSheetCreationCommand;
use RpgBot\CharacterSheets\Application\Query\CharacterSheetQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
#[Route('api/slack_slash/characters')]
class SlackSlashCharacterController extends AbstractController
{
    public function __construct(
        private CharacterSheetQuery $sheetQuery,
        private MessageBusInterface $commandBus,
        private LoggerInterface $logger,
        private SlackCall $slackCall,
        private SlackEvent $slackEvent,
        private SlackFormView $slackFormView,
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
        $character = $this->sheetQuery->getBySlackId($requestData->id());

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
                $requestData->id(),
                $requestData->userName()
            )
        );

        return $this->render(
            'characters/create.html.twig',
            [
                'name' => $requestData->userName(),
            ]
        );
    }

    #[Route('/test', name: 'characters_test', methods: ['POST'])]
    public function modalCreate(Request $request): Response
    {
        $this->logger->info(print_r($request->request->all(), true));
        $requestData = $this->slackCall->extractCallData($request->request->all());

        $client = $this->slackEvent->client();
        $client->viewsOpen(
            [
                'trigger_id' => $requestData->triggerId(),
                'view' => $this->slackFormView->characterForm($requestData->userName()),
            ]
        );

        return new Response();
    }

    #[Route('/receive-modal', name: 'characters_create_modal', methods: ['POST'])]
    public function modalReceive(Request $request): Response
    {
        $requestData = $request->request->all();
        $this->logger->info(print_r(json_decode($requestData['payload']), true));

        if ($this->slackCall->isSaveAction($requestData)) {
            $characterClass = $this->slackCall->extractStateFromAction($requestData);
        }

        return new Response();
    }
}
