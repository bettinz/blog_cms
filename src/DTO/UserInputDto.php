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
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }
}
