<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/news", name="news_")
 */
class NewsController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function showNewsDashboard(): Response
    {
        return $this->render('news/dashboard.html.twig');
    }
}
