<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Reservation;
use App\Services\ReservationCheckIfCarReservedService;
use App\Services\ReservationIsDateValidatedService;
use App\Services\ReservationStoreService;
use App\Services\ReservationUpdateService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationsController extends AbstractController
{
    // declare private varaible for entity manager
    private $entityManager;
    
    // declare private services
    private $reservationStoreService;
    private $reservationIsDateValidatedService;
    private $reservationCheckIfCarReservedService;
    private $reservationUpdateService;
    
    // constructor
    public function __construct(EntityManagerInterface $entityManager, ReservationStoreService $reservationStoreService,
        ReservationIsDateValidatedService $reservationIsDateValidatedService, 
        ReservationCheckIfCarReservedService $reservationCheckIfCarReservedService,
        ReservationUpdateService $reservationUpdateService)
    {
        // set the entity manager variable
        $this->entityManager = $entityManager;
        // set reservation services
        $this->reservationStoreService = $reservationStoreService;
        $this->reservationIsDateValidatedService = $reservationIsDateValidatedService;
        $this->reservationCheckIfCarReservedService = $reservationCheckIfCarReservedService;
        $this->reservationUpdateService = $reservationUpdateService;
    }

    #[Route('/api/reservations', name: 'api_reservations_store', methods: ['POST'])]
    public function storeReservation(Request $request): Response
    {
        // get request parameters
        $parameters = [
            'dateDebut' => $request->get('dateDebut'),
            'dateFin' => $request->get('dateFin'),
            'carId' => $request->get('carId')
        ];
        // parameters validation
        foreach ($parameters as $param){
            if (!$param) return $this->json(['response' => 'failed', 'status' => 400, 
                    'message' => "Reservation failed, request parameters are required (dateDebut, dateFin, carId)."]);
        }

        // prepare datetime parameters
        $dateDebut = new DateTime($parameters['dateDebut']);
        $dateFin = new DateTime($parameters['dateFin']);
    
        // dateDebut and dateFin validation (dateDebut shouldn't be greater than dateFin)
        $validated = $this->reservationIsDateValidatedService
            ->isReservationDateValidated($dateDebut, $dateFin);
        if(!$validated){
            return $this->json(['response' => 'failed', 'status' => 400, 'message' => "Reservation failed, start date shouldn't be greater than end date."]);
        }
        
        // get car by its id
        $carId = $parameters['carId'];
        $car = $this->entityManager->getRepository(Car::class)
            ->find($carId);

        // check if the car is already reserved
        $reserved = $this->reservationCheckIfCarReservedService
            ->checkIfCarReserved($car, $dateDebut, $dateFin);
        if($reserved){
            return $this->json(['response' => 'failed', 'status' => 400, 'message' => "Reservation failed, this car is already reserved in this date."]);
        }

        // get authenticated user
        $user = $this->getUser();

        // use reservation store Service to store the reservation (reserve)
        $this->reservationStoreService
            ->storeReservation($dateDebut, $dateFin, $car, $user);
        
        // return json response
        return $this->json(['response' => 'success', 'status' => 200, 'message' => 'Reservation created successfully.']);
    }

    #[Route('/api/reservations/{id}', name: 'api_reservations_update', methods: ['PUT'])]
    public function updateReservation(Request $request, int $id): Response
    {
        // get request parameters
        $parameters = [
            'dateDebut' => $request->get('dateDebut'),
            'dateFin' => $request->get('dateFin'),
            'carId' => $request->get('carId')
        ];
        // parameters validation
        foreach ($parameters as $param){
            if (!$param) return $this->json(['response' => 'failed', 'status' => 400, 
                    'message' => "Reservation update failed, request parameters are required (dateDebut, dateFin, carId)."]);
        }

        // get the reservation
        $reservation = $this->entityManager->getRepository(Reservation::class)
            ->find($id);

        // get the reservation user
        $user = $reservation->getUser();

        // compare the reservation user and the authenticated user, return response failed in contrast case
        if($user != $this->getUser()){
            return $this->json(['response' => 'failed', 'status' => 400, 'message' => "Reservation update failed, This user should not updated other users' reservations."]);
        }

        // prepare datetime parameters
        $dateDebut = new DateTime($parameters['dateDebut']);
        $dateFin = new DateTime($parameters['dateFin']);

        // dateDebut and dateFin validation (dateDebut shouldn't be greater than dateFin)
        $validated = $this->reservationIsDateValidatedService
            ->isReservationDateValidated($dateDebut, $dateFin);
        if(!$validated){
            return $this->json(['response' => 'failed', 'status' => 400, 'message' => "Reservation update failed, start date shouldn't be greater than end date."]);
        }

        // get car by its id
        $carId = $parameters['carId'];
        $car = $this->entityManager->getRepository(Car::class)
            ->find($carId);

        // use reservation update Service to update the reservation
        $this->reservationUpdateService
            ->updateReservation($reservation, $car, $dateDebut, $dateFin);

        return $this->json(['response' => 'success', 'status' => 200, 'message' => 'Reservation updated successfully.']);
    }

    #[Route('/api/reservations/{id}', name: 'api_reservations_delete', methods: ['DELETE'])]
    public function deleteReservation(int $id): Response
    {
        // get the reservation
        $reservation = $this->entityManager->getRepository(Reservation::class)
            ->find($id);
        
        // get the reservation user
        $user = $reservation->getUser();

        // compare the reservation user and the authenticated user, return response failed in contrast case
        if($user != $this->getUser()){
            return $this->json(['response' => 'failed', 'status' => 400, 'message' => "Reservation delete failed, This user should not delete other users' reservations."]);
        }

        // delete reservation
        $this->entityManager->remove($reservation);
        $this->entityManager->flush();

        // return json response
        return $this->json(['response' => 'success', 'status' => 200, 'message' => 'Reservation deleted successfully.']);
    }

}
