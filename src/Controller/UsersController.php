<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\User;
use App\Services\UserReservationsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    // declare private varaible for entity manager
    private $entityManager;

    // declare private services
    private $userReservationsService;

    // constructor
    public function __construct(EntityManagerInterface $entityManager, UserReservationsService $userReservationsService){
        // set the entity manager variable
        $this->entityManager = $entityManager;
        // set user services
        $this->userReservationsService = $userReservationsService;
    }

    #[Route('/api/users/{id}/reservations', name: 'api_users_reservations', methods: ['GET'])]
    public function showReservations(int $id): Response
    {
        // get user first
        $user = $this->entityManager->getRepository(User::class)->find($id);
        
        // compare the selected user(id) and the authenticated user, return response failed in contrast case
        if($user != $this->getUser()){
            return $this->json(['response' => 'response failed', 'message' => "This user should not see other users' reservations."]);
        }

        // get reservations that belong to user
        $reservations = $this->userReservationsService->getUserReservations($user);

        // return json response
        return $this->json(['response' => 'success', 'result' => $reservations]);
    }
}
