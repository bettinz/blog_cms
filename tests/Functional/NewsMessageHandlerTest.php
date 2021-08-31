<?php

namespace App\Tests\Functional;

use App\Entity\News;
use App\MessageHandler\NewsHandler;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class NewsMessageHandlerTest extends KernelTestCase
{
    private EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    }

    public function testInvokeHandler()
    {
        $category = $this->entityManager->getRepository('App:Category')->findOneBy([
            'name' => 'prima-pagina',
        ]);
        $author = $this->entityManager->getRepository('App:User')->findOneBy([
            'email' => 'editor@blog.com',
        ]);
        $tag = $this->entityManager->getRepository('App:Tag')->findOneBy([
            'name' => 'sport',
        ]);

        $news = new News();
        $news->setTitle('news');
        $news->setDescription('news');
        $news->setCategory($category);
        $news->setAuthor($author);
        $news->addTag($tag);

        $newsHandler = new NewsHandler($this->entityManager);
        $newsHandler($news);

        /**
         * @var News $persistedNews
         */
        $persistedNews = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'news',
        ]);

        $this->assertEquals($persistedNews->getTitle(), $news->getTitle());
        $this->assertEquals($persistedNews->getDescription(), $news->getDescription());
        $this->assertEquals($persistedNews->getCategory(), $news->getCategory());
        $this->assertEquals($persistedNews->getAuthor(), $news->getAuthor());
        $this->assertEquals($persistedNews->getTags(), $news->getTags());
    }
}
