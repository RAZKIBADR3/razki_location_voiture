<?php

namespace App\Services;

use App\Entity\Reservation;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserReservationsService
{
    // declare an the entity manager variable
    private $entityManager;
    // constructor
    public function __construct(EntityManagerInterface $entityManager)
    {
        // set the entity manager variable
        $this->entityManager = $entityManager;
    }

    public function getUserReservations(User $user){
        // query builder for getting user reservation
        $queryBuilder = $this->entityManager->getRepository(Reservation::class)
            ->createQueryBuilder('r')
                ->select('r.id', 'c.id AS car', 'r.dateDebut', 'r.dateFin')
                ->leftJoin('r.car', 'c') // left join with 'car'
                ->where('r.user = :user')
                ->setParameter('user', $user)
                ->orderBy('r.id');
        return $queryBuilder->getQuery()->execute();
    }
}