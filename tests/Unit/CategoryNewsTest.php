<?php

namespace App\Tests\Unit;

use App\Entity\Category;
use App\Entity\News;
use PHPUnit\Framework\TestCase;

class CategoryNewsTest extends TestCase
{
    public function testNewsCollectionInCategory()
    {
        $news = new News();
        $category = new Category();
        $category->addNews($news);

        $this->assertContains($news, $category->getNews());

        $category->removeNews($news);

        $this->assertNotContains($news, $category->getNews());
    }
}
