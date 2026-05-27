<?php
namespace App\Validation;

use App\Request\LoginRequest;
use App\Request\RegisterUserRequest;

class Validator
{
    public function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? null;
            $value = is_string($value) ? trim($value) : $value;

            if (($fieldRules['required'] ?? false) && $this->isEmpty($value)) {
                $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
                continue;
            }

            if ($this->isEmpty($value)) {
                continue;
            }

            if (isset($fieldRules['min']) && mb_strlen((string) $value) < $fieldRules['min']) {
                $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . " must be at least {$fieldRules['min']} characters";
            }

            if (isset($fieldRules['max']) && mb_strlen((string) $value) > $fieldRules['max']) {
                $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . " must be less than {$fieldRules['max']} characters";
            }

            if (($fieldRules['email'] ?? false) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . ' must be a valid email';
            }

            if (($fieldRules['strong'] ?? false) && !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', (string) $value)) {
                $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . ' must contain uppercase, lowercase, and a number';
            }

            if (isset($fieldRules['match']) && (($data[$fieldRules['match']] ?? '') !== $value)) {
                $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . ' must match ' . str_replace('_', ' ', $fieldRules['match']);
            }
        }

        return $errors;
    }

    public function validateRegister(array $data): array
    {
        if (isset($data['name']) && !isset($data['username'])) {
            $data['username'] = $data['name'];
        }

        $rules = RegisterUserRequest::rules();

        if (isset($rules['username'])) {
            $rules['name'] = $rules['username'];
            unset($rules['username']);
        }

        return $this->validate($data, $rules);
    }

    public function validateLogin(array $data): array
    {
        return $this->validate($data, LoginRequest::rules());
    }

    public function isValid(array $errors): bool
    {
        return empty($errors);
    }

    private function isEmpty(mixed $value): bool
    {
        return $value === null || $value === '' || (is_array($value) && empty($value));
    }
}