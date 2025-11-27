<?php

namespace App\Tests\Behat;

use App\Command\ParkVehicleCommand;
use App\Command\RegisterVehicleCommand;
use App\Handler\CreateFleetHandler;
use App\Handler\ParkVehicleHandler;
use App\Handler\RegisterVehicleHandler;
use App\Query\GetVehicleLocationQuery;
use App\QueryHandler\GetVehicleLocationHandler;
use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Hook\BeforeScenario;
use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Doctrine\ORM\EntityManagerInterface;
use Domain\Exception\VehicleAlreadyRegisteredException;
use Domain\Exception\VehicleAlreadyParkedException;
use Domain\Model\Fleet;
use Domain\Repository\FleetRepositoryInterface;
use Domain\ValueObject\FleetId;
use Domain\ValueObject\Location;
use Domain\ValueObject\UserId;
use Domain\ValueObject\VehiclePlate;
use PHPUnit\Framework\Assert;
use Throwable;

final class FeatureContext implements Context
{
    private const MY_USER_ID = 'my-user-id';
    private const ANOTHER_USER_ID = 'another-user-id';
    private const VEHICLE_PLATE = 'AZE-RTY-123';

    private Fleet $myFleet;
    private Fleet $anotherUserFleet;
    private VehiclePlate $vehiclePlate;
    private Location $location;

    private ?Throwable $lastException = null;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private FleetRepositoryInterface $repository,
        private CreateFleetHandler $createFleetHandler,
        private RegisterVehicleHandler $registerVehicleHandler,
        private ParkVehicleHandler $parkVehicleHandler,
        private GetVehicleLocationHandler $vehicleLocationHandler
    ) {}

    #[Given('my fleet')]
    public function myFleet(): void
    {
        $this->myFleet = $this->repository->create(new UserId(self::MY_USER_ID));
    }

    #[Given('the fleet of another user')]
    public function theFleetOfAnotherUser(): void
    {
        $this->anotherUserFleet = $this->repository->create(new UserId(self::ANOTHER_USER_ID));
    }

    #[Given('a vehicle')]
    public function aVehicle(): void
    {
        $this->vehiclePlate = new VehiclePlate(self::VEHICLE_PLATE);
    }

    #[Given('I have registered this vehicle into my fleet')]
    public function iHaveRegisteredThisVehicleIntoMyFleet(): void
    {
        $command = new RegisterVehicleCommand($this->myFleet->getFleetId()->getValue(), $this->vehiclePlate->getValue());
        ($this->registerVehicleHandler)($command);
    }

    #[Given("this vehicle has been registered into the other user's fleet")]
    public function thisVehicleHasBeenRegisteredIntoTheOtherUsersFleet(): void
    {
        $command = new RegisterVehicleCommand($this->anotherUserFleet->getFleetId()->getValue(), $this->vehiclePlate->getValue());
        ($this->registerVehicleHandler)($command);
    }

    #[Given('a location')]
    public function aLocation(): void
    {
        $this->Location = $this->getLocation(); 
    }

    #[Given('my vehicle has been parked into this location')]
    public function myVehicleHasBeenParkedIntoThisLocation(): void
    {
        $location = $this->getLocation();
        $command = new ParkVehicleCommand(
            $this->myFleet->getFleetId()->getValue(),
            $this->vehiclePlate->getValue(),
            $location->getLatitude(),
            $location->getLongitude(),
            $location->getAltitude()
        );

        ($this->parkVehicleHandler)($command);
    }

    #[When('I try to register this vehicle into my fleet')]
    #[When('I register this vehicle into my fleet')]
    public function iRegisterThisVehicleIntoMyFleet(): void
    {
        $command = new RegisterVehicleCommand($this->myFleet->getFleetId()->getValue(), $this->vehiclePlate->getValue());

        try {
            ($this->registerVehicleHandler)($command);
        } catch (Throwable $e) {
            $this->lastException = $e;
        }
    }

    #[When('I park my vehicle at this location')]
    #[When('I try to park my vehicle at this location')]
    public function iParkMyVehicleAtThisLocation(): void
    {
        $location = $this->getLocation();
        $command = new ParkVehicleCommand(
            $this->myFleet->getFleetId()->getValue(),
            $this->vehiclePlate->getValue(),
            $location->getLatitude(),
            $location->getLongitude(),
            $location->getAltitude()
        );

        try {
            ($this->parkVehicleHandler)($command);
        } catch (Throwable $e) {
            $this->lastException = $e;
        }
    }

    #[Then('this vehicle should be part of my vehicle fleet')]
    public function thisVehicleShouldBePartOfMyVehicleFleet(): void
    {
        Assert::assertNull($this->lastException, 'Registration failed with an unexpected exception.');

        $fleet = $this->repository->findOneByFleetId(FleetId::generate($this->myFleet->getFleetId()->getValue()));

        Assert::assertTrue(
            $fleet->isVehicleRegistered($this->vehiclePlate),
            sprintf(
                'Vehicle "%s" was not found in the fleet "%s".',
                $this->vehiclePlate->getValue(),
                $this->myFleet->getFleetId()->getValue()
            )
        );
    }

    #[Then('I should be informed this vehicle has already been registered into my fleet')]
    public function iShouldBeInformedThisVehicleHasAlreadyBeenRegisteredIntoMyFleet(): void
    {
        Assert::assertInstanceOf(VehicleAlreadyRegisteredException::class, $this->lastException, 'Expected a VehicleAlreadyRegisteredException.');
    }

    #[Then('the known location of my vehicle should verify this location')]
    public function theKnownLocationOfMyVehicleShouldVerifyThisLocation(): void
    {
        $query = new GetVehicleLocationQuery(
            $this->myFleet->getFleetId()->getValue(),
            $this->vehiclePlate->getValue());
        $location = ($this->vehicleLocationHandler)($query);

        Assert::assertNull($this->lastException, 'Parking failed with an unexpected exception.');
        Assert::assertNotNull($location, 'Vehicle location was not found.');
        Assert::assertTrue($this->getLocation()->equals($location), 'Vehicle location does not match expected location.');
    }

    #[Then('I should be informed that my vehicle is already parked at this location')]
    public function iShouldBeInformedThatMyVehicleIsAlreadyParkedAtThisLocation(): void
    {
        Assert::assertInstanceOf(VehicleAlreadyParkedException::class, $this->lastException, 'Expected a VehicleAlreadyParkedException.');
    }

    private function getLocation(): Location
    {
        return new Location(1.00, 1.00, 1.00); 
    }
}
