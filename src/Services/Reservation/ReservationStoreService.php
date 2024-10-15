<?php

namespace App\Services;

use App\Entity\Car;
use App\Entity\Reservation;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class ReservationStoreService
{    
    // declare an the entity manager variable
    private $entityManager;

    // constructor
    public function __construct(EntityManagerInterface $entityManager)
    {
        // set the entity manager variable
        $this->entityManager = $entityManager;
    }

    public function storeReservation(DateTime $dateDebut, Datetime $dateFin, Car $car, User $user){
        // store the reservation (reserve)
        $reservation = new Reservation();
        $reservation->setDateDebut($dateDebut);
        $reservation->setDateFin($dateFin);
        $reservation->setCar($car);
        $reservation->setUser($user);
        // Persist changes to the database
        $this->entityManager->persist($reservation);
        $this->entityManager->flush();
    }
}
