<?php

namespace App\Models;

class User
{
    private ?int $user_id = null;
    private string $name = '';
    private string $email = '';
    private string $passwordHash = '';
    private ?string $created_at = null;

    // =========================
    // GETTERS
    // =========================
    public function getId(): ?int { return $this->user_id; }
    public function getName(): string { return $this->name; }
    public function getEmail(): string { return $this->email; }
    public function getPasswordHash(): string { return $this->passwordHash; }

    // =========================
    // SETTERS
    // =========================
    public function setId(?int $id): void { $this->user_id = $id; }
    public function setName(string $name): void { $this->name = $name; }
    public function setEmail(string $email): void { $this->email = $email; }
    public function setPasswordHash(string $hash): void { $this->passwordHash = $hash; }
    public function setCreatedAt(?string $createdAt): void { $this->created_at = $createdAt; }

    // =========================
    // OUTPUT ONLY (API / VIEW)
    // =========================
    public function toArray(): array
    {
        return [
            'id' => $this->user_id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at,
        ];
    }
}