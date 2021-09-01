<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

class GetMe extends AbstractController
{
    public function __invoke(): UserInterface
    {
        return $this->getUser();
    }
}
