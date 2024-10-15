<?php

namespace App\Services;

use App\Entity\Car;
use Doctrine\ORM\EntityManagerInterface;

class CarGetDetailsService
{
    // declare an the entity manager variable
    private $entityManager;
    // constructor
    public function __construct(EntityManagerInterface $entityManager)
    {
        // set the entity manager variable
        $this->entityManager = $entityManager;
    }

    public function getCarDetails(int $id){
        // use query builder to get car by id
        $querybuilder = $this->entityManager->getRepository(Car::class)
            ->createQueryBuilder('c')
                ->select('c.id', 'c.nom', 'c.prix', 'c.model', 'c.entreprise', 'c.color')
                ->where('c.id = :id')
                ->setParameter('id', $id);

        // retrieve result
        $results = $querybuilder->getQuery()->execute();
        return $results ? $results[0] : [];
    }
}