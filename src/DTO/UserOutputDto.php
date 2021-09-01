<?php

namespace App\DTO;

use ApiPlatform\Core\Annotation\ApiProperty;

class UserOutputDto
{
    /**
     * @var int
     * @ApiProperty(identifier=true)
     */
    public int $id;

    public string $email;

    public array $roles;

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
