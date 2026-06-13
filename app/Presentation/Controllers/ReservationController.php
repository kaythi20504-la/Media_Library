<?php

namespace App\Presentation\Controllers;

use App\Application\Reservation\UseCases\ReserveMediaUseCase;
use App\Domain\Reservation\Repositories\ReservationRepositoryInterface;

class ReservationController extends BaseController
{
    public function __construct(
        private ReserveMediaUseCase $reserveMediaUseCase,
        private ReservationRepositoryInterface $reservationRepository
    ) {}

    public function reserve(): void
    {
        $this->requireLogin();

        $userId = (int) $_SESSION['user_id'];

        $mediaId = filter_input(
            INPUT_POST,
            'media_id',
            FILTER_VALIDATE_INT
        );

        $notes = trim($_POST['notes'] ?? '');

        // validate media id
        if (!$mediaId) {
            $_SESSION['flash'] = "Invalid media ID";

            $this->redirect(
                BASE_URL . '/Public/index.php?page=catalog'
            );
            return;
        }

        // execute use case
        $result = $this->reserveMediaUseCase->execute(
            $userId,
            (int)$mediaId,
            $notes
        );

        $_SESSION['flash'] =
            $result['message'] ?? 'Reservation successful';

        $this->redirect(
            BASE_URL .
            '/Public/index.php?page=details&id=' .
            $mediaId
        );
    }

    public function myReservations(): void
    {
        $this->requireLogin();

        $userId = (int) $_SESSION['user_id'];

        $reservations =
            $this->reservationRepository->findByUser($userId);

        $this->view('my', [
            'reservations' => $reservations
        ]);
    }
}