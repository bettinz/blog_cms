<?php

namespace App\DTO;

class UserOutputDto
{
    private int $id;

    private string $email;

    private array $roles;

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }
}
