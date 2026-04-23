<?php

namespace App\Repository;

use App\Entity\Carpooling;
use Doctrine\DBAL\Types\Types;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Carpooling>
 */
class CarpoolingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Carpooling::class);
    }

    /**
     * @return Carpooling[]
     */
    public function searchUpcomingTrips(?string $departureCity, ?string $destinationCity, ?\DateTimeImmutable $departureDate): array
    {
        $qb = $this->createQueryBuilder('c')
            ->addSelect('driver', 'vehicle')
            ->join('c.driver', 'driver')
            ->join('c.vehicle', 'vehicle')
            ->andWhere('c.departureAt >= :now')
            ->andWhere('c.seatsAvailable > 0')
            ->setParameter('now', new \DateTimeImmutable(), Types::DATETIME_IMMUTABLE)
            ->orderBy('c.departureAt', 'ASC');

        if ($departureCity) {
            $qb
                ->andWhere('LOWER(c.originCity) LIKE LOWER(:departureCity)')
                ->setParameter('departureCity', '%'.$departureCity.'%');
        }

        if ($destinationCity) {
            $qb
                ->andWhere('LOWER(c.destinationCity) LIKE LOWER(:destinationCity)')
                ->setParameter('destinationCity', '%'.$destinationCity.'%');
        }

        if ($departureDate) {
            $startOfDay = $departureDate->setTime(0, 0, 0);
            $endOfDay = $departureDate->setTime(23, 59, 59);

            $qb
                ->andWhere('c.departureAt BETWEEN :startOfDay AND :endOfDay')
                ->setParameter('startOfDay', $startOfDay, Types::DATETIME_IMMUTABLE)
                ->setParameter('endOfDay', $endOfDay, Types::DATETIME_IMMUTABLE);
        }

        return $qb
            ->setMaxResults(24)
            ->getQuery()
            ->getResult();
    }
}
