<?php

namespace App\Service;

use App\DTO\UserInputCreateDto;
use App\DTO\UserInputUpdateDto;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TransformUserInputDtoToUser
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function transform(UserInputCreateDto $dto): User
    {
        $user = new User();
        $user->setEmail($dto->getEmail());
        $user->setPassword($this->hasher->hashPassword($user, $dto->getPassword()));
        $user->setRoles($dto->getRoles());

        return $user;
    }

    public function transformExisting(UserInputUpdateDto $dto, User $user): User
    {
        if ($dto->getEmail()) {
            $user->setEmail($dto->getEmail());
        }
        if ($dto->getRoles()) {
            $user->setRoles($dto->getRoles());
        }
        if ($dto->getPassword()) {
            $user->setPassword($this->hasher->hashPassword($user, $dto->getPassword()));
        }

        return $user;
    }
}
