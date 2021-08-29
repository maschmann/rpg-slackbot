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
        private string $triggerId,
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
        string $triggerId,
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
            $args,
            $triggerId
        );
    }

    public function teamId(): string
    {
        return $this->teamId;
    }

    public function channelId(): string
    {
        return $this->channelId;
    }

    public function channelName(): string
    {
        return $this->channelName;
    }

    public function callingUser(): string
    {
        return $this->callingUser;
    }

    public function callingUserId(): string
    {
        return $this->callingUserId;
    }

    public function userName(): string
    {
        return $this->userName;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function command(): string
    {
        return $this->command;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function args(): string
    {
        return $this->args;
    }

    public function id(): string
    {
        return $this->userId . "_" . $this->teamId;
    }

    public function triggerId(): string
    {
        return $this->triggerId;
    }

}
