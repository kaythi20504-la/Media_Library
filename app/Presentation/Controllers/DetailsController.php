<?php

declare(strict_types=1);

namespace App\Presentation\Controllers;

use App\Application\Catalog\UseCases\GetCatalogItemUseCase;
use App\Infrastructure\Persistence\Reservation\ReservationRepository;

class DetailsController extends BaseController
{
    public function __construct(
        private GetCatalogItemUseCase $itemUseCase,
        private ReservationRepository $reservationRepo
    ) {}

    public function show(): void
    {
        $this->requireLogin();

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            $this->redirect(BASE_URL . '/Public/index.php?page=catalog');
            return;
        }

        $item = $this->itemUseCase->execute($id);

        if (!$item) {
            $this->redirect(BASE_URL . '/Public/index.php?page=catalog');
            return;
        }

        $userId = $_SESSION['user_id'] ?? null;

        $reservation = null;

        if ($userId) {
            $reservation = $this->reservationRepo
                ->findUserReservation((int)$userId, (int)$id);
        }

        $this->view('details', [
            'item' => $item,
            'user' => $this->user(),
            'reservation' => $reservation
        ]);
    }
}