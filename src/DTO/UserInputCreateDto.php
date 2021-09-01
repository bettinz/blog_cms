<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UserInputCreateDto
{
    /**
     * @Assert\NotBlank
     */
    public ?string $email = '';

    /**
     * @Assert\NotBlank
     */
    public ?string $password = '';

    /**
     * @Assert\NotBlank
     *
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
     * @return string[]|null
     */
    public function getRoles(): ?array
    {
        return $this->roles;
    }
}
