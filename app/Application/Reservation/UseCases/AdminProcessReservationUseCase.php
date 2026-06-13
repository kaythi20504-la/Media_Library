<?php

namespace App\Application\Reservation\UseCases;

use App\Domain\Reservation\Repositories\ReservationRepositoryInterface;

class AdminProcessReservationUseCase
{
    public function __construct(
        private ReservationRepositoryInterface $repo
    ) {}

    /**
     * Process reservation (approve / reject)
     */
    public function execute(
        int $reservationId,
        int $adminId,
        string $action,
        string $notes = ''
    ): array {

        // -----------------------
        // APPROVE
        // -----------------------
        if ($action === 'approve') {

            $this->repo->approve($reservationId, $adminId);

            return [
                'success' => true,
                'message' => 'Reservation approved successfully'
            ];
        }

        // -----------------------
        // REJECT
        // -----------------------
        if ($action === 'reject') {

            $this->repo->reject($reservationId, $notes);

            return [
                'success' => true,
                'message' => 'Reservation rejected'
            ];
        }

        return [
            'success' => false,
            'message' => 'Invalid action'
        ];
    }
}