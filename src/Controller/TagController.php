<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tags", name="tags_")
 */
class TagController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function showTagsDashboard(): Response
    {
        return $this->render('tags/index.html.twig');
    }

    /**
     * @Route("/add", name="add")
     */
    public function addTag(): Response
    {
        return $this->render('tags/add.html.twig');
    }
}
