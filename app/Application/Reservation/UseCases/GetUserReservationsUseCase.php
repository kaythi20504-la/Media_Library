<?php

namespace App\Application\Reservation\UseCases;

use App\Domain\Reservation\Repositories\ReservationRepositoryInterface;

class GetUserReservationsUseCase
{
    public function __construct(
        private ReservationRepositoryInterface $repo
    ) {}

    public function execute(int $userId): array
    {
        return $this->repo->findByUser($userId);
    }
}