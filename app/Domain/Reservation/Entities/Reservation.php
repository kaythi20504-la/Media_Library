<?php

namespace App\Domain\Reservation\Entities;

class Reservation
{
    public function __construct(
        private ?int $id,
        private int $userId,
        private int $mediaId,
        private string $status = 'pending',
        private ?string $notes = null
    ) {}

    public function getId(): ?int { return $this->id; }
    public function getUserId(): int { return $this->userId; }
    public function getMediaId(): int { return $this->mediaId; }
    public function getStatus(): string { return $this->status; }
    public function getNotes(): ?string { return $this->notes; }
}