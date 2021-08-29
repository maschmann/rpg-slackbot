<?php

declare(strict_types=1);

namespace App\Command;

use RpgBot\CharacterSheets\Application\Command\CharacterSheet\CharacterSheetCreationCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CharacterCreateCommand extends Command
{
    public function __construct(
        private MessageBusInterface $commandBus
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('character:create')
            ->addArgument('id', InputArgument::REQUIRED)
            ->addArgument('name', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->commandBus->dispatch(
            new CharacterSheetCreationCommand(
                $input->getArgument('id'),
                $input->getArgument('name')
            )
        );

        return Command::SUCCESS;
    }
}
