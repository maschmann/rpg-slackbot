<?php

declare(strict_types=1);

namespace App\Controller;

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
#[Route('api')]
class CharacterController extends AbstractController
{
    public function __construct(
        private CharacterSheetQuery $sheetQuery,
        private MessageBusInterface $commandBus,
        private LoggerInterface $logger,
    ) {
    }

    #[Route('/characters', name: 'character_list', methods: ['POST'])]
    public function list(Request $request): Response
    {
        $this->logger->debug(print_r($request->request->get('text'), true));
        $characterList = $this->sheetQuery->getAll();

        $characters = [];
        foreach ($characterList as $item) {
            $characters[] = $item->getName() . " " . $item->getLevel();
        }

        return $this->render(
            'characters/list.html.twig',
            [
                'characters' => $characters
            ]
        );
    }

    #[Route('/character', name: 'character_by_name', methods: ['POST'])]
    public function byName(Request $request): Response
    {
        // \<\@(?P<user_id>[a-zA-Z]+)\|(?P<user_name>.+)\>(?P<args>.+)

        $character = $this->sheetQuery->getByName($name);

        return $this->render(
            'characters/character.html.twig',
            [
                'character' => $character,
            ]
        );
    }

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
