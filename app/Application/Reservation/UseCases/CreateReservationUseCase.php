<?php

namespace App\Application\Reservation\UseCases;

use App\Domain\Reservation\Entities\Reservation;
use App\Domain\Reservation\Repositories\ReservationRepositoryInterface;

class CreateReservationUseCase
{
    public function __construct(
        private ReservationRepositoryInterface $repo
    ) {}

    public function execute(
        int $userId,
        int $mediaId
    ): void {

        $reservation = new Reservation(
            null,
            $userId,
            $mediaId
        );

        $this->repo->create(
            $reservation
        );
    }
}