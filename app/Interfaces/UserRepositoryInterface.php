<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface UserRepositoryInterface extends BaseInterface
{
    
    public function findByEmail(string $email): ?array;
}