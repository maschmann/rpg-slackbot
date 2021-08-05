<?php

declare(strict_types=1);

namespace App\Slack\Infrastructure;

use App\Slack\Dto\CallDataDto;
use App\Slack\Infrastructure\Exception\InvalidBodyException;

/**
 * it's a bit over the top to encapsulate here and handle stuff via DTOs, but it makes it way cleaner
 */
class SlackCall
{
    /**
     * @param array<string,string> $requestData
     * @return CallDataDto
     */
    public function extractCallData(array $requestData): CallDataDto
    {
        $teamId = $requestData['team_id'] && $requestData['team_id'] !== ''
            ? $requestData['team_id'] : throw new InvalidBodyException("There was no team_id");
        $channelId = $requestData['channel_id'] ?? '';
        $channelName = $requestData['channel_name'] ?? '';
        $callingUser = $requestData['user_name'] ?? '';
        $callingUserId = $requestData['user_id'] ?? '';
        $command = $requestData['command'] && $requestData['command'] !== ''
            ? $requestData['command'] : throw new InvalidBodyException("No command given");
        $text = $requestData['text'] ?? '';

        $matches = [];
        preg_match_all('/<@(?P<user_id>[a-zA-Z]+)\|(?P<user_name>.+)>(?P<args>.+)?/', $text, $matches);

        $userName = !empty($matches['user_name'][0]) ? $matches['user_name'][0] : $callingUser;
        $userId = !empty($matches['user_id'][0]) ? $matches['user_id'][0] : $callingUserId;
        $args = !empty($matches['args'][0]) ? trim($matches['args'][0]) : '';

        return CallDataDto::create(
            $teamId,
            $channelId,
            $channelName,
            $callingUser,
            $callingUserId,
            $userName,
            $userId,
            $command,
            $text,
            $args
        );
    }
}
