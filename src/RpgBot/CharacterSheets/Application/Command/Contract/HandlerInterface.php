<?php

declare(strict_types=1);

namespace RpgBot\CharacterSheets\Application\Command\Contract;

interface HandlerInterface
{
    public function __invoke(CommandInterface $command): void;
}
