<?php

namespace App\Presentation\Controllers\Admin;

use App\Presentation\Controllers\BaseController;
use App\Infrastructure\Persistence\Reservation\ReservationRepository;

class ReservationAdminController extends BaseController
{
    public function __construct(
        private ReservationRepository $repo
    ) {}

    public function index(): void
    {
        $this->requireLogin();

        if (($_SESSION['role'] ?? 'user') !== 'admin') {
            die("Access denied");
        }

        $reservations = $this->repo->findAll();

        $this->view('admin/reservations', [
            'reservations' => $reservations
        ]);
    }

   public function process(): void
{
    $this->requireLogin();

    if (($_SESSION['role'] ?? 'user') !== 'admin') {
        die("Access denied");
    }

    $id = (int) ($_POST['id'] ?? 0);
    $action = $_POST['action'] ?? '';
    $notes = $_POST['notes'] ?? null;

    if ($action === 'approve') {
        $this->repo->approve($id, $_SESSION['user_id']);
    }

    if ($action === 'reject') {
        $this->repo->reject($id, $notes);
    }

    $this->redirect(BASE_URL . '/Public/index.php?page=admin/reservations');
}
}