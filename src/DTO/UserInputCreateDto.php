<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UserInputCreateDto
{
    /**
     * @var string
     * @Assert\NotBlank
     */
    public ?string $email = '';

    /**
     * @var string
     * @Assert\NotBlank
     */
    public ?string $password = '';

    /**
     * @var array
     * @Assert\NotBlank
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
