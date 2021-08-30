<?php

namespace App\Service;

use App\DTO\UserOutputDto;
use App\Entity\User;

class TransformUserToUserOutputDto
{
    public function transform(User $user): UserOutputDto
    {
        $userOutputDto = new UserOutputDto();
        $userOutputDto->setId($user->getId());
        $userOutputDto->setEmail($user->getEmail());
        $userOutputDto->setRoles($user->getRoles());

        return $userOutputDto;
    }
}
