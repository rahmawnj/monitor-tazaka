<?php

namespace App\Support;

use Illuminate\Contracts\Auth\Authenticatable;

class HardcodedUser implements Authenticatable
{
    public function __construct(
        public readonly string $username,
        public readonly string $email,
    ) {}

    public function getAuthIdentifierName(): string
    {
        return 'email';
    }

    public function getAuthIdentifier(): string
    {
        return $this->email;
    }

    public function getAuthPasswordName(): string
    {
        return 'password';
    }

    public function getAuthPassword(): string
    {
        return '';
    }

    public function getRememberToken(): string
    {
        return '';
    }

    public function setRememberToken($value): void
    {
        // Remember-me is intentionally disabled for this hardcoded account.
    }

    public function getRememberTokenName(): string
    {
        return '';
    }
}
