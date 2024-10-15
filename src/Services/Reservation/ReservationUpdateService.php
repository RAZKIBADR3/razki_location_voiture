<?php

namespace App\Services;

use App\Entity\Car;
use App\Entity\Reservation;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class ReservationUpdateService
{
    // declare an the entity manager variable
    private $entityManager;

    // constructor
    public function __construct(EntityManagerInterface $entityManager)
    {
        // set the entity manager variable
        $this->entityManager = $entityManager;
    }

    public function updateReservation(Reservation $reservation, Car $car, DateTime $dateDebut, DateTime $dateFin){
        // reservation update
        $reservation->setCar($car);
        $reservation->setDateDebut($dateDebut);
        $reservation->setDateFin($dateFin);
        // Persist changes to the database
        $this->entityManager->persist($reservation);
        $this->entityManager->flush();
    }
}