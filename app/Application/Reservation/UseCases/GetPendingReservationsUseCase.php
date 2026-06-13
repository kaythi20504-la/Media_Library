<?php

namespace App\Application\Reservation\UseCases;

use App\Domain\Reservation\Repositories\ReservationRepositoryInterface;

class GetPendingReservationsUseCase
{
    public function __construct(
        private ReservationRepositoryInterface $repo
    ) {}

    public function execute(): array
    {
        return $this->repo->findPending();
    }
}