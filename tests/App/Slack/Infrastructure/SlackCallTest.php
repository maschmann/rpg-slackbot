<?php

declare(strict_types=1);

namespace Tests\App\Slack\Infrastructure;

use App\Slack\Dto\CallDataDto;
use App\Slack\Infrastructure\Exception\InvalidBodyException;
use App\Slack\Infrastructure\SlackCall;
use PHPUnit\Framework\TestCase;

class SlackCallTest extends TestCase
{
    public function testCanExtractDataFromRequest(): void
    {
        $sampleData = [
            'team_id' => 'TEUQPLYAF',
            'channel_id' => 'CEU62U6FJ',
            'channel_name' => 'allgemein',
            'user_name' => 'maschmann',
            'user_id' => 'UEVVARTKR',
            'command' => '/rpg-characters',
            'text' => '<@UEVVARTKR|maschmann> test',
        ];

        $slackCall = new SlackCall();
        $extractedData = $slackCall->extractCallData($sampleData);

        $this->assertInstanceOf(CallDataDto::class, $extractedData);
        // test the extracted target users
        $this->assertSame('UEVVARTKR', $extractedData->getUserId());
        $this->assertSame('maschmann', $extractedData->getUserName());
        $this->assertSame('test', $extractedData->getArgs());
    }

    public function testFailsWhenTeamIdIsMissing(): void
    {
        $this->expectException(InvalidBodyException::class);

        $sampleData = [
            'team_id' => '',
            'channel_id' => 'CEU62U6FJ',
            'channel_name' => 'allgemein',
            'user_name' => 'maschmann',
            'user_id' => 'UEVVARTKR',
            'command' => '/rpg-characters',
            'text' => '<@UEVVARTKR|maschmann> test',
        ];

        (new SlackCall())->extractCallData($sampleData);
    }

    public function testFailsWhenCommandIsMissing(): void
    {
        $this->expectException(InvalidBodyException::class);

        $sampleData = [
            'team_id' => 'TEUQPLYAF',
            'channel_id' => 'CEU62U6FJ',
            'channel_name' => 'allgemein',
            'user_name' => 'maschmann',
            'user_id' => 'UEVVARTKR',
            'command' => '',
            'text' => '<@UEVVARTKR|maschmann> test',
        ];

        (new SlackCall())->extractCallData($sampleData);
    }
}
