<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function showCategoryDashboard(): Response
    {
        return $this->render('category/index.html.twig');
    }

    /**
     * @Route("/add", name="add")
     */
    public function addCategory(): Response
    {
        return $this->render('category/add.html.twig');
    }
}
