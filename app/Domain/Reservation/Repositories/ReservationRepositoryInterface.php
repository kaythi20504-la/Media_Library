<?php

namespace App\Domain\Reservation\Repositories;

use App\Domain\Reservation\Entities\Reservation;

interface ReservationRepositoryInterface
{
    public function create(Reservation $reservation): void;

    public function findByUser(int $userId): array;

    public function findPending(): array;

    public function approve(int $id, int $adminId): void;

    public function reject(int $id, string $notes = ''): void;

    public function hasPendingReservation(int $userId, int $mediaId): bool;
}