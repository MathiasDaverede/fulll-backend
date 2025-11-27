<?php

namespace App\CommandCli;

use App\Command\ParkVehicleCommand;
use App\Handler\ParkVehicleHandler;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'fleet:park-vehicle',
    description: 'Park a vehicle on a location',
)]
class ParkVehicleCliCommand extends Command
{
    public function __construct(protected ParkVehicleHandler $handler)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('fleetId', InputArgument::REQUIRED, 'Fleet Id')
            ->addArgument('vehiclePlateNumber', InputArgument::REQUIRED, 'Vehicle plate number')
            ->addArgument('lat', InputArgument::REQUIRED, 'Latitude')
            ->addArgument('lng', InputArgument::REQUIRED, 'Longitude')
            ->addArgument('alt', InputArgument::OPTIONAL, 'Altitude')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $fleetId = $input->getArgument('fleetId');
        $vehiclePlate = $input->getArgument('vehiclePlateNumber');
        $lat = $input->getArgument('lat');
        $lng = $input->getArgument('lng');
        $alt = $input->getArgument('alt');

       ($this->handler)(new ParkVehicleCommand($fleetId, $vehiclePlate, $lat, $lng, $alt));

        $io->success(sprintf(
            'Vehicle "%s" of fleet "%s" has been successfully parked at location : %s, %s, %s',
                $vehiclePlate,
                $fleetId,
                $lat,
                $lng,
                $alt
        ));

        return Command::SUCCESS;
    }
}
