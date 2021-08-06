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

    /**
     * @return string
     */
    public function getWorkspace(): string
    {
        return $this->workspace;
    }

    /**
     * @return string
     */
    public function getChannel(): string
    {
        return $this->channel;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getId(): string
    {
        return $this->userId . "_" . $this->workspace;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }
}
