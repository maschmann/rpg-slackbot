<?php

declare(strict_types=1);

namespace App\Slack\Infrastructure;

use App\Slack\Dto\UserEventDto;
use App\Slack\Infrastructure\Exception\InvalidActionException;
use App\Slack\Infrastructure\Exception\InvalidTypeException;
use App\Slack\Dto\Contract\EventDtoInterface;
use App\Slack\Dto\HandshakeEventDto;
use App\Slack\Infrastructure\Exception\InvalidBodyException;
use JoliCode\Slack\Client;
use JoliCode\Slack\ClientFactory;

class SlackEvent
{
    public const TYPE_APP_MENTION = 'app_mention';

    public const ACTION_LIST_CHARACTERS = 'list_characters';

    public const ACTION_GET_CHARACTER = 'get_character';

    /**
     * @var array<int,string>
     */
    private array $validTypes;

    public function __construct(private string $token)
    {
        $this->validTypes[] = self::TYPE_APP_MENTION;
    }

    public function client(): Client
    {
        return ClientFactory::create($this->token);
    }

    public function handleEvent(string $body, Client $client): EventDtoInterface
    {
        $jsonBody = json_decode($body, true);
        if (empty($jsonBody)) {
            throw new InvalidBodyException('Received an empty event body');
        }

        $handshake = $this->handleHandshake($jsonBody);
        if (!empty($handshake)) {
            return HandshakeEventDto::create($handshake);
        }
        $userId = $this->extractUser($jsonBody);
        return UserEventDto::create(
            $this->extractWorkspace($jsonBody),
            $this->extractChannel($jsonBody),
            $userId,
            $this->getUserName($client, $userId),
            $this->extractType($jsonBody),
            $this->extractAction($jsonBody)
        );
    }

    /**
     * @param array<mixed,mixed> $body
     * @return array<string,string>
     */
    private function handleHandshake(array $body): array
    {
        $handshake = [];

        if (
            array_key_exists('type', $body)
            && 'url_verification' === $body['type']
        ) {
            $handshake['challenge'] = $body['challenge'];
        }

        return $handshake;
    }

    private function isValidType(string $type): bool
    {
        if (in_array($type, $this->validTypes)) {
            return true;
        }

        return false;
    }

    /**
     * @param array<mixed,mixed> $body
     * @return string
     */
    private function extractType(array $body): string
    {
        $type = !empty($body['event']) ? $body['event']['type'] : 'unknown_type';

        if (!$this->isValidType($type)) {
            throw new InvalidTypeException(sprintf("The type %s is not supported", $type));
        }

        return $type;
    }

    /**
     * @param array<mixed,mixed> $body
     * @return string
     */
    private function extractAction(array $body): string
    {
        $event = !empty($body['event']) ? $body['event']['text'] : 'undefined_action';

        return match (true) {
            (1 === preg_match('/(list.?characters)/i', $event)) => self::ACTION_LIST_CHARACTERS,
            (1 === preg_match('/(get.?character)/i', $event)) => self::ACTION_GET_CHARACTER,
            default => throw new InvalidActionException("The action is not supported"),
        };
    }

    /**
     * @param array<mixed,mixed> $body
     * @return string
     */
    private function extractUser(array $body): string
    {
        return $body['event']['user'];
    }

    /**
     * @param array<mixed,mixed> $body
     * @return string
     */
    private function extractChannel(array $body): string
    {
        return $body['event']['channel'];
    }

    /**
     * @param array<mixed,mixed> $body
     * @return string
     */
    private function extractWorkspace(array $body): string
    {
        return $body['event']['team'];
    }

    private function getUserName(Client $client, string $userId): string
    {
        $userName = '';
        /**
         * @var \JoliCode\Slack\Api\Model\UsersInfoGetResponse200 $user
         */
        $user = $client->usersInfo(['user' => $userId]);
        if (null !== $user) {
            /**
             * @var \JoliCode\Slack\Api\Model\ObjsUser $userObj
             */
            $userObj = $user->getUser();
            if (null !== $userObj) {
                $userName = (string)$userObj->getName();
            }
        }

        return $userName;
    }
}
