<?php

namespace App\Tests\Functional\ChangeNewsPublicationStatus\Published;

use App\Entity\News;
use App\Tests\Functional\CommonFunctions;
use Doctrine\ORM\EntityManager;

class PublishedNewsPrePublicateTest extends CommonFunctions
{
    private EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = CommonFunctions::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testPublishedNewsPrePublicateAsEditor(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Bocciardo, doppio oro alle Paralimpiadi',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/pre-publicate';

        $response = static::editorClient()->request('GET', $url);

        $this->assertResponseStatusCodeSame(403);
    }

    public function testPublishedNewsPrePublicateAsPublisher(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Bocciardo, doppio oro alle Paralimpiadi',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/pre-publicate';

        $response = static::publisherClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(403);
    }

    public function testPublishedNewsPrePublicateAsReviewer(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Bocciardo, doppio oro alle Paralimpiadi',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/pre-publicate';

        $response = static::reviewerClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(200);

        $this->assertJsonContains([
            '@context' => '/api/contexts/News',
            '@id' => '/api/news/'.$id,
            'publicationStatus' => 'published',
        ]);
    }

    public function testPublishedNewsPrePublicateAsAdmin(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Bocciardo, doppio oro alle Paralimpiadi',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/pre-publicate';

        $response = static::adminClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(200);

        $this->assertJsonContains([
            '@context' => '/api/contexts/News',
            '@id' => '/api/news/'.$id,
            'publicationStatus' => 'published',
        ]);
    }
}
