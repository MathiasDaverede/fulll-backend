<?php

namespace Infra\Persistence;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Domain\Model\Fleet;
use Domain\Repository\FleetRepositoryInterface;
use Domain\ValueObject\FleetId;
use Domain\ValueObject\UserId;
use Infra\Persistence\Entity\FleetEntity;
use Infra\Persistence\Mapper\FleetMapper;

/**
 * @extends ServiceEntityRepository<FleetEntity>
 */
class FleetRepository extends ServiceEntityRepository implements FleetRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FleetEntity::class);
    }

    public function findOneByFleetId(FleetId $fleetId): ?Fleet
    {   
        $entity = $this->findEntityByFleetId($fleetId);

        return !is_null($entity) ? FleetMapper::mapEntityToDomain($entity) : null;
    }

    public function create(UserId $userId): Fleet
    {
        $fleet = new Fleet($userId, FleetId::generate());
        $this->save($fleet, true);

        return $fleet;
    }

    public function save(Fleet $fleet, bool $isCreation = false): void
    {
        $entityManager = $this->getEntityManager();

        if ($isCreation) {
            $entity = new FleetEntity();
            $entityManager->persist($entity);
        } else {
            $entity = $this->findEntityByFleetId($fleet->getFleetId());
        }

        FleetMapper::mapDomainToEntity($fleet, $entity);

        $entityManager->flush();
    }

    private function findEntityByFleetId(FleetId $fleetId): ?FleetEntity
    {   
        return $this->createQueryBuilder('f')
            ->where('f.fleetId = :fleetId')
            ->setParameter('fleetId', $fleetId->getValue())
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
