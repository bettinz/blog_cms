<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserPersister
{
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function persist(User $user): User
    {
        $this->manager->persist($user);
        $this->manager->flush();

        return $user;
    }
}
