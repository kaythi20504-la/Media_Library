<?php

namespace App\Application\Reservation\UseCases;

use App\Domain\Reservation\Entities\Reservation;
use App\Domain\Reservation\Repositories\ReservationRepositoryInterface;

class ReserveMediaUseCase
{
    public function __construct(
        private ReservationRepositoryInterface $repo
    ) {}

    public function execute(int $userId, int $mediaId, string $notes = ''): array
{
    // 1. check duplicate
    if ($this->repo->hasPendingReservation($userId, $mediaId)) {
        return [
            'success' => false,
            'message' => 'You already reserved this item (pending)'
        ];
    }

    // 2. create reservation
    $reservation = new Reservation(
        null,
        $userId,
        $mediaId,
        'pending',
        $notes
    );

    $this->repo->create($reservation);

    return [
        'success' => true,
        'message' => 'Reservation created successfully'
    ];
}

}