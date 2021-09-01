<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetMe extends AbstractController
{
    public function __invoke()
    {
        return $this->getUser();
    }
}
