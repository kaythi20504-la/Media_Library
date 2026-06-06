<?php

namespace App\Controllers\Api;

use App\Services\UserService;

class ApiUserController
{
    private UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    // =========================
    // GET ALL USERS
    // =========================
    public function index(): void
    {
        $this->json($this->service->getUsers());
    }

    // =========================
    // GET SINGLE USER
    // =========================
    public function show(int $id): void
{
    header('Content-Type: application/json');

    echo json_encode(
        $this->service->getUser($id),
        JSON_PRETTY_PRINT
    );
}

    // =========================
    // CREATE USER
    // =========================
    public function store(): void
    {
        $data = $this->getJsonBody();

        $result = $this->service->createUser($data);

        $this->json([
            'success' => $result
        ]);
    }

    // =========================
    // UPDATE USER
    // =========================
    public function update(int $id): void
{
    header('Content-Type: application/json');

    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid JSON input'
        ]);
        return;
    }

    $result = $this->service->updateUser($id, $data);

    echo json_encode([
        'success' => $result
    ]);
}
    // =========================
    // DELETE USER
    // =========================
  public function delete(int $id): void
{
    header('Content-Type: application/json');

    $result = $this->service->deleteUser($id);

    echo json_encode(['success' => $result]);
}

    // =====================================================
    // 🔥 HELPER: READ JSON INPUT (DRY)
    // =====================================================
    private function getJsonBody(): array
    {
        $input = file_get_contents("php://input");
        return json_decode($input, true) ?? [];
    }

    // =====================================================
    // 🔥 HELPER: JSON RESPONSE (DRY)
    // =====================================================
    private function json($data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }
}