<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use App\Service\UserPersister;

class UserDataPersister implements DataPersisterInterface
{
    private UserPersister $userPersister;

    public function __construct(UserPersister $userPersister)
    {
        $this->userPersister = $userPersister;
    }

    /**
     * @param User $data
     */
    public function supports($data): bool
    {
        return $data instanceof User;
    }

    /**
     * @param User $data
     */
    public function persist($data): User
    {
        return $this->userPersister->persist($data);
    }

    /**
     * @param User $data
     */
    public function remove($data): void
    {
        $this->userPersister->remove($data);
    }
}
