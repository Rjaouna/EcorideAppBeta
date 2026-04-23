<?php

namespace App\Repository;

use App\Entity\Booking;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Booking>
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    public function hasPassengerBooking(int $tripId, int $passengerId): bool
    {
        return null !== $this->createQueryBuilder('b')
            ->select('b.id')
            ->andWhere('b.trip = :trip')
            ->andWhere('b.passager = :passenger')
            ->setParameter('trip', $tripId)
            ->setParameter('passenger', $passengerId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Booking[]
     */
    public function findPassengerBookings(User $user): array
    {
        return $this->createQueryBuilder('b')
            ->addSelect('trip', 'driver', 'vehicle')
            ->join('b.trip', 'trip')
            ->join('trip.driver', 'driver')
            ->join('trip.vehicle', 'vehicle')
            ->andWhere('b.passager = :user')
            ->setParameter('user', $user)
            ->orderBy('trip.departureAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
