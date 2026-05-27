<?php
namespace App\Request;

class LoginRequest
{
    public static function rules(): array
    {
        return [
            'email' => [
                'required' => true,
                'email' => true,
            ],
            'password' => [
                'required' => true,
                'min' => 6,
            ],
        ];
    }
}