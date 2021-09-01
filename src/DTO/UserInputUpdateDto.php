<?php

namespace App\DTO;

class UserInputUpdateDto
{
    public ?string $email = '';

    public ?string $password = '';

    /**
     * @var string[]
     */
    public ?array $roles = [];

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return string[]
     */
    public function getRoles(): ?array
    {
        return $this->roles;
    }
}
