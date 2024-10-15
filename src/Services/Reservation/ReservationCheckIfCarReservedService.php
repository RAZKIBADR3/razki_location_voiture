<?php 

namespace App\Services;

use App\Entity\Car;
use App\Entity\Reservation;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class ReservationCheckIfCarReservedService
{
    // declare an the entity manager variable
    private $entityManager;
    // constructor
    public function __construct(EntityManagerInterface $entityManager)
    {
        // set the entity manager variable
        $this->entityManager = $entityManager;
    }

    public function checkIfCarReserved(Car $car, DateTime $dateDebut, DateTime $dateFin){
        // query builder for checking if there is reservations for same car with similiar dates
        $queryBuilder = $this->entityManager->getRepository(Reservation::class)
            ->createQueryBuilder('r')
                ->select('r.id')
                ->where("r.car = :car")
                ->andWhere('(r.dateDebut <= :dateFin AND r.dateFin >= :dateDebut)')
                ->setParameter('car', $car)
                ->setParameter('dateDebut', $dateDebut)
                ->setParameter('dateFin', $dateFin);
        $reservations = $queryBuilder->getQuery()->execute();

        // return true if there is a reservation
        return $reservations ? true : false;
    }
}