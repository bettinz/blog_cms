<?php

namespace App\Service;

use App\DTO\UserInputDto;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TransformUserInputDtoToUser
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function transform(UserInputDto $dto): User
    {
        $user = new User();
        $user->setEmail($dto->getEmail());
        $user->setPassword($this->hasher->hashPassword($user, $dto->getPassword()));
        $user->setRoles($dto->getRoles());

        return $user;
    }
}
