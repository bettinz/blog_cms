<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UserInputDto
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    private string $email;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private string $password;

    /**
     * @var array
     * @Assert\NotBlank()
     */
    private array $roles;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }
}
