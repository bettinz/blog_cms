<?php

namespace App\Tests\Unit;

use App\Entity\News;
use App\Entity\Tag;
use PHPUnit\Framework\TestCase;

class TagsNewsTest extends TestCase
{
    public function testTagCollectionInNews()
    {
        $news = new News();
        $tag = new Tag();

        $tag->addNews($news);

        $this->assertContains($news, $tag->getNews());
        $tag->removeNews($news);
        $this->assertNotContains($news, $tag->getNews());
    }
}
