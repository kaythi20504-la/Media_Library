<?php

namespace App\Infrastructure\Persistence\Reservation;

use PDO;
use App\Domain\Reservation\Entities\Reservation;
use App\Domain\Reservation\Repositories\ReservationRepositoryInterface;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function __construct(private PDO $pdo) {}

    public function create(Reservation $reservation): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO reservations (user_id, media_id, status, notes)
            VALUES (:user_id, :media_id, 'pending', :notes)
        ");

        $stmt->execute([
            'user_id' => $reservation->getUserId(),
            'media_id' => $reservation->getMediaId(),
            'notes' => $reservation->getNotes()
        ]);
    }

  public function findByUser(int $userId): array
{
    $stmt = $this->pdo->prepare("
        SELECT 
            r.id,
            r.media_id,
            r.status,
            r.notes,
            r.reserved_at,
            m.title,
            m.img
        FROM reservations r
        JOIN media m ON r.media_id = m.media_id
        WHERE r.user_id = :user_id
        ORDER BY r.reserved_at DESC
    ");

    $stmt->execute([
        'user_id' => $userId
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public function findAll(): array
{
    $stmt = $this->pdo->prepare("
        SELECT 
            r.id,
            r.user_id,
            r.media_id,
            r.status,
            r.notes,
            r.reserved_at,
            u.name AS user_name,
            m.title AS media_title,
            m.img AS media_img
        FROM reservations r
        JOIN users u ON u.id = r.user_id
        JOIN media m ON m.media_id = r.media_id
        ORDER BY r.reserved_at DESC
    ");

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function updateStatus(int $id, string $status): void
{
    $stmt = $this->pdo->prepare("
        UPDATE reservations
        SET status = :status
        WHERE id = :id
    ");

    $stmt->execute([
        'status' => $status,
        'id' => $id
    ]);
}

    public function findPending(): array
    {
        $stmt = $this->pdo->query("
            SELECT * FROM reservations
            WHERE status = 'pending'
            ORDER BY reserved_at DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function approve(int $id, int $adminId): void
{
    $stmt = $this->pdo->prepare("
        UPDATE reservations
        SET status='approved',
            approved_by=:admin,
            approved_at=NOW()
        WHERE id=:id
    ");

    $stmt->execute([
        'admin' => $adminId,
        'id' => $id
    ]);
}

   public function reject(int $id, string $notes = ''): void
{
    $stmt = $this->pdo->prepare("
        UPDATE reservations
        SET status='rejected',
            notes=:notes
        WHERE id=:id
    ");

    $stmt->execute([
        'notes' => $notes,
        'id' => $id
    ]);
}

 public function hasPendingReservation(int $userId, int $mediaId): bool
{
    $stmt = $this->pdo->prepare("
        SELECT COUNT(*) FROM reservations
        WHERE user_id = :user_id
        AND media_id = :media_id
        AND status = 'pending'
    ");

    $stmt->execute([
        'user_id' => $userId,
        'media_id' => $mediaId
    ]);

    return (int)$stmt->fetchColumn() > 0;
}

public function findUserReservation(int $userId, int $mediaId): ?array
{
    $stmt = $this->pdo->prepare("
        SELECT id, status, notes
        FROM reservations
        WHERE user_id = :user_id
        AND media_id = :media_id
        ORDER BY id DESC
        LIMIT 1
    ");

    $stmt->execute([
        'user_id' => $userId,
        'media_id' => $mediaId
    ]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result ?: null;
}
}