<?php

namespace App\Controller;

use App\Entity\Car;
use App\Services\CarGetDetailsService;
use App\Services\CarListAvaibleCarsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarsController extends AbstractController
{
    // declare an the entity manager variable
    private $entityManager;

    // declare private services
    private $carListAvaibleCarsService;
    private $carGetDetailsService;

    // constructor
    public function __construct(EntityManagerInterface $entityManager, CarListAvaibleCarsService $carListAvaibleCarsService, 
        CarGetDetailsService $carGetDetailsService)
    {
        // set the entity manager variable
        $this->entityManager = $entityManager;
        // set car services
        $this->carListAvaibleCarsService = $carListAvaibleCarsService;
        $this->carGetDetailsService = $carGetDetailsService;
    }

    #[Route('/api/cars', name: 'api_cars', methods: ['GET'])]
    public function showCars(): Response
    {
        // user ListAvaibleCars Service to list avaible cars
        $cars = $this->carListAvaibleCarsService->listAvaibleCars();
        // return json response
        return $this->json(['response' => 'success', 'result' => $cars]);
    }

    #[Route('/api/cars/{id}', name: 'api_car', methods: ['GET'])]
    public function showCar(int $id): Response
    {
        // use car get Service to show car by id
        $car = $this->carGetDetailsService
            ->getCarDetails($id);

        // return json response
        return $this->json(['response' => 'success', 'result' => $car]);
    }
}
