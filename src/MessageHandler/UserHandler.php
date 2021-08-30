<?php

namespace App\MessageHandler;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserHandler
{
    private EntityManagerInterface $manager;

    /**
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke(User $user)
    {
        $this->manager->persist($user);
        $this->manager->flush();
    }
}
