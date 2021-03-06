<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserCollectionProvider
{
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return User[]
     */
    public function provide(): array
    {
        return $this->manager->getRepository(User::class)->findAll();
    }
}
