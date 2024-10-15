<?php

namespace App\Services;

use App\Entity\Car;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class CarListAvaibleCarsService
{
    // declare an the entity manager variable
    private $entityManager;
    // constructor
    public function __construct(EntityManagerInterface $entityManager)
    {
        // set the entity manager variable
        $this->entityManager = $entityManager;
    }

    public function listAvaibleCars(){
        $currentDate = new DateTime();
        // get avaibles cars by a query builder
        $querybuilder = $this->entityManager->getRepository(Car::class)
            ->createQueryBuilder('c')
                ->select('c.id', 'c.nom', 'c.prix', 'c.model', 'c.entreprise', 'c.color')
                ->leftJoin('c.reservations', 'r') // left join with 'reservations'
                ->where('r.id IS NULL') // No reservation
                ->orWhere(':currentDate < r.dateDebut OR :currentDate > r.dateFin') // Available if current date is outside reservation dates
                ->setParameter('currentDate', $currentDate)
                ->orderBy('c.id');
        return $querybuilder->getQuery()->execute();
    }
}