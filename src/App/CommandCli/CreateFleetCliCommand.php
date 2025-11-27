<?php

namespace App\CommandCli;

use App\Command\CreateFleetCommand;
use App\Handler\CreateFleetHandler;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'fleet:create',
    description: 'Create a new fleet',
)]
class CreateFleetCliCommand extends Command
{
    public function __construct(protected CreateFleetHandler $handler)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('userId', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $userId = $input->getArgument('userId');
        
        $fleetId = ($this->handler)(new CreateFleetCommand($userId));

        $io->success($fleetId);

        return Command::SUCCESS;
    }
}
