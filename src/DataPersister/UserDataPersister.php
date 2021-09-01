<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use App\Service\UserPersister;

class UserDataPersister implements DataPersisterInterface
{
    private UserPersister $userPersister;

    /**
     * @param UserPersister $userPersister
     */
    public function __construct(UserPersister $userPersister)
    {
        $this->userPersister = $userPersister;
    }

    public function supports($data): bool
    {
        return $data instanceof User;
    }

    public function persist($data)
    {
        return $this->userPersister->persist($data);
    }

    public function remove($data)
    {
        return $this->userPersister->remove($data);
    }
}
