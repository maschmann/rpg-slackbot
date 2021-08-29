<?php

declare(strict_types=1);

namespace App\Slack\Dto;

use App\Slack\Dto\Contract\EventDtoInterface;

class UserEventDto implements EventDtoInterface
{
    private function __construct(
        private string $workspace,
        private string $channel,
        private string $userId,
        private string $userName,
        private string $type,
        private string $action,
    ) {
    }

    public static function create(
        string $workspace,
        string $channel,
        string $userId,
        string $userName,
        string $type,
        string $action,
    ): EventDtoInterface {
        return new self(
            $workspace,
            $channel,
            $userId,
            $userName,
            $type,
            $action
        );
    }

    public function workspace(): string
    {
        return $this->workspace;
    }

    public function channel(): string
    {
        return $this->channel;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function id(): string
    {
        return $this->userId . "_" . $this->workspace;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function action(): string
    {
        return $this->action;
    }

    public function userName(): string
    {
        return $this->userName;
    }
}
