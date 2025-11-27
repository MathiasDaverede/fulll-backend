<?php

namespace App\CommandCli;

use App\Command\RegisterVehicleCommand;
use App\Handler\RegisterVehicleHandler;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'fleet:register-vehicle',
    description: 'Register a vehicle in a fleet',
)]
class RegisterVehicleCliCommand extends Command
{
    public function __construct(protected RegisterVehicleHandler $handler)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('fleetId', InputArgument::REQUIRED, 'Fleet Id')
            ->addArgument('vehiclePlateNumber', InputArgument::REQUIRED, 'Vehicle plate number')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $fleetId = $input->getArgument('fleetId');
        $vehiclePlate = $input->getArgument('vehiclePlateNumber');

        ($this->handler)(new RegisterVehicleCommand($fleetId, $vehiclePlate));

        $io->success(sprintf(
            'Vehicle "%s" has been successfully registered in fleet "%s"',
                $vehiclePlate,
                $fleetId
        ));

        return Command::SUCCESS;
    }
}
