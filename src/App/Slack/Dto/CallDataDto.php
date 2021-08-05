<?php

declare(strict_types=1);

namespace App\Slack\Dto;

class CallDataDto
{
    private function __construct(
        private string $teamId,
        private string $channelId,
        private string $channelName,
        private string $callingUser,
        private string $callingUserId,
        private string $userName,
        private string $userId,
        private string $command,
        private string $text,
        private string $args,
    ) {
    }

    public static function create(
        string $teamId,
        string $channelId,
        string $channelName,
        string $callingUser,
        string $callingUserId,
        string $userName,
        string $userId,
        string $command,
        string $text,
        string $args,
    ): self {
        return new self(
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

    /**
     * @return string
     */
    public function getTeamId(): string
    {
        return $this->teamId;
    }

    /**
     * @return string
     */
    public function getChannelId(): string
    {
        return $this->channelId;
    }

    /**
     * @return string
     */
    public function getChannelName(): string
    {
        return $this->channelName;
    }

    /**
     * @return string
     */
    public function getCallingUser(): string
    {
        return $this->callingUser;
    }

    /**
     * @return string
     */
    public function getCallingUserId(): string
    {
        return $this->callingUserId;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getArgs(): string
    {
        return $this->args;
    }
}
