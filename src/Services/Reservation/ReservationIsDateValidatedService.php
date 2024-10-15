<?php

namespace App\Services;

use DateTime;

class ReservationIsDateValidatedService
{

   // dateDebut and dateFin validation (dateDebut shouldn't be greater than dateFin)
    public function isReservationDateValidated(DateTime $dateDebut, Datetime $dateFin){
        return $dateDebut < $dateFin;
    }

}
