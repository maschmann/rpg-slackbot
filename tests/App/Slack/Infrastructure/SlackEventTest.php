<?php

declare(strict_types=1);

namespace Tests\App\Slack\Infrastructure;

use App\Slack\Dto\HandshakeEventDto;
use App\Slack\Dto\UserEventDto;
use App\Slack\Infrastructure\Exception\InvalidActionException;
use App\Slack\Infrastructure\Exception\InvalidBodyException;
use App\Slack\Infrastructure\Exception\InvalidTypeException;
use App\Slack\Infrastructure\SlackEvent;
use JoliCode\Slack\Client;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SlackEventTest extends TestCase
{
    private string $bodyRaw = <<< EOF
{
    "token": "BSQVpMaG2Rgsn0gyKhK3RGpK",
    "team_id": "TEUQPLYAF",
    "api_app_id": "A02A01J93KK",
    "event": {
        "client_msg_id": "57491520-e1e9-416e-941e-d2f90d469a8e",
        "type": "app_mention",
        "text": "<@U029MCJNLQJ> Hey :smile: list characters!",
        "user": "UEVVARTKR",
        "ts": "1627761571.000800",
        "team": "TEUQPLYAF",
        "blocks": [
            {
                "type": "rich_text",
                "block_id": "CFv",
                "elements": [
                    {
                        "type": "rich_text_section",
                        "elements": [
                            {
                                "type": "user",
                                "user_id": "U029MCJNLQJ"
                            },
                            {
                                "type": "text",
                                "text": " Hey "
                            },
                            {
                                "type": "emoji",
                                "name": "smile"
                            }
                        ]
                    }
                ]
            }
        ],
        "channel": "CEU62U6FJ",
        "event_ts": "1627761571.000800"
    },
    "type": "event_callback",
    "event_id": "Ev029ZLH5JCC",
    "event_time": 1627761571,
    "authorizations": [
        {
            "enterprise_id": null,
            "team_id": "TEUQPLYAF",
            "user_id": "U029MCJNLQJ",
            "is_bot": true,
            "is_enterprise_install": false
        }
    ],
    "is_ext_shared_channel": false,
    "event_context": "4-eyJldCI6ImFwcF9tZW50aW9uIiwidGlkIjoiVEVVUVBMWUFGIiwiYWlkIjoiQTAyQTAxSjkzS0siLCJjaWQiOiJDRVU2MlU2RkoifQ"
}
EOF;

    public function testCreatesAClient(): void
    {
        $slack = new SlackEvent('some_token');
        $client = $slack->client();

        $this->assertInstanceOf(Client::class, $client);
    }

    public function testCanHandleHandshake(): void
    {
        $slack = new SlackEvent('some_token');
        $handshakeRaw = <<< EOF
{
    "token": "Jhj5dZrVaK7ZwHHjRyZWjbDl",
    "challenge": "3eZbrw1aBm2rZgRNFdxV2595E9CY3gmdALWMmHkvFXO7tYXAYM8P",
    "type": "url_verification"
}
EOF;
        $handshake = $slack->handleEvent($handshakeRaw, $this->getClientMock());
        $this->assertInstanceOf(HandshakeEventDto::class, $handshake);
        $this->assertSame(
            '3eZbrw1aBm2rZgRNFdxV2595E9CY3gmdALWMmHkvFXO7tYXAYM8P',
            $handshake->getChallenge()
        );
    }

    public function testCanExtractEventData(): void
    {
        $slack = new SlackEvent('some_token');
        $event = $slack->handleEvent($this->bodyRaw, $this->getClientMock());
        $this->assertInstanceOf(UserEventDto::class, $event);
        $this->assertSame(SlackEvent::TYPE_APP_MENTION, $event->getType());
        $this->assertSame(SlackEvent::ACTION_LIST_CHARACTERS, $event->getAction());
    }

    public function testThrowsInvalidBodyException(): void
    {
        $this->expectException(InvalidBodyException::class);
        $slack = new SlackEvent('some_token');
        $slack->handleEvent('{}', $this->getClientMock());
    }

    public function testThrowsInvalidTypeException(): void
    {
        $body =  <<< EOF
{
    "event": {
        "client_msg_id": "57491520-e1e9-416e-941e-d2f90d469a8e",
        "type": "invalid_type_DUH",
        "text": "<@U029MCJNLQJ> Hey :smile: list characters!",
        "user": "UEVVARTKR",
        "ts": "1627761571.000800",
        "team": "TEUQPLYAF",
        "channel": "CEU62U6FJ"
    }
}
EOF;
        $this->expectException(InvalidTypeException::class);
        $slack = new SlackEvent('some_token');
        $slack->handleEvent($body, $this->getClientMock());
    }

    public function testThrowsInvalidActionException(): void
    {
        $body =  <<< EOF
{
    "event": {
        "client_msg_id": "57491520-e1e9-416e-941e-d2f90d469a8e",
        "type": "app_mention",
        "text": "<@U029MCJNLQJ> Hey :smile: no real action",
        "user": "UEVVARTKR",
        "ts": "1627761571.000800",
        "team": "TEUQPLYAF",
        "channel": "CEU62U6FJ"
    }
}
EOF;

        $this->expectException(InvalidActionException::class);
        $slack = new SlackEvent('some_token');
        $slack->handleEvent($body, $this->getClientMock());
    }

    private function getClientMock(): MockObject
    {
        return $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
