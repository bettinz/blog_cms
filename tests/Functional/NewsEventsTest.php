<?php

namespace App\Tests\Functional;

use App\Entity\News;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class NewsEventsTest extends KernelTestCase
{
    private EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    }

    public function testNewsEventsPublicationStatusCreationAndUpdateDate()
    {
        $category = $this->entityManager->getRepository('App:Category')->findOneBy([
            'name' => 'prima-pagina',
        ]);
        $author = $this->entityManager->getRepository('App:User')->findOneBy([
            'email' => 'editor@blog.com',
        ]);
        $news = new News();
        $news->setTitle('news');
        $news->setDescription('news');
        $news->setCategory($category);
        $news->setAuthor($author);
        $this->entityManager->persist($news);

        $now = new \DateTime();
        $now = $now->format('Y-m-d H:i:s');

        $this->entityManager->flush();

        $persistedNews = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'news',
        ]);

        $this->assertEquals($persistedNews->getCreationDate()->format('Y-m-d H:i:s'), $now);
        $this->assertEquals($persistedNews->getUpdateDate()->format('Y-m-d H:i:s'), $now);
        $this->assertEquals($persistedNews->getPublicationStatus(), 'idea');
    }
}
