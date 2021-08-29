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
            'text' => '<@XXXXXX|DerBoese> test',
        ];

        $slackCall = new SlackCall();
        $extractedData = $slackCall->extractCallData($sampleData);

        $this->assertInstanceOf(CallDataDto::class, $extractedData);
        // test the extracted target users
        $this->assertSame('XXXXXX', $extractedData->userId());
        $this->assertSame('DerBoese', $extractedData->userName());
        $this->assertSame('test', $extractedData->args());
    }

    public function testUseFallbackDataForUserAndId(): void
    {
        $sampleData = [
            'team_id' => 'TEUQPLYAF',
            'channel_id' => 'CEU62U6FJ',
            'channel_name' => 'allgemein',
            'user_name' => 'maschmann',
            'user_id' => 'UEVVARTKR',
            'command' => '/rpg-characters',
            'text' => '',
        ];

        $slackCall = new SlackCall();
        $extractedData = $slackCall->extractCallData($sampleData);

        $this->assertInstanceOf(CallDataDto::class, $extractedData);
        // test the extracted target users
        $this->assertSame('UEVVARTKR', $extractedData->userId());
        $this->assertSame('maschmann', $extractedData->userName());
        $this->assertSame('', $extractedData->args());
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
