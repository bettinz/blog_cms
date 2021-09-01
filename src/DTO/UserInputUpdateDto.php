<?php

namespace App\DTO;

class UserInputUpdateDto
{
    /**
     * @var string
     */
    public ?string $email = '';

    /**
     * @var string
     */
    public ?string $password = '';

    /**
     * @var array
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
     * @return array
     */
    public function getRoles(): ?array
    {
        return $this->roles;
    }
}
