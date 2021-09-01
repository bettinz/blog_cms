<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user", name="user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function showUserDashboard(): Response
    {
        return $this->render('user/index.html.twig');
    }

    /**
     * @Route("/add", name="add")
     */
    public function addUser(): Response
    {
        return $this->render('user/add.html.twig');
    }
}
