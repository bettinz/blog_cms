<?php

namespace App\Tests\Functional\ChangeNewsPublicationStatus\Published;

use App\Entity\News;
use App\Tests\Functional\CommonFunctions;
use Doctrine\ORM\EntityManager;

class PublishedNewsValidateTest extends CommonFunctions
{
    private EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = CommonFunctions::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testPublishedNewsValidateAsEditor(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Bocciardo, doppio oro alle Paralimpiadi',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/validate';

        $response = static::editorClient()->request('GET', $url);

        $this->assertResponseStatusCodeSame(200);

        $this->assertJsonContains([
            '@context' => '/api/contexts/News',
            '@id' => '/api/news/'.$id,
            'publicationStatus' => 'published',
        ]);
    }

    public function testPublishedNewsValidateAsPublisher(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Bocciardo, doppio oro alle Paralimpiadi',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/validate';

        $response = static::publisherClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(403);
    }

    public function testPublishedNewsValidateAsReviewer(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Bocciardo, doppio oro alle Paralimpiadi',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/validate';

        $response = static::reviewerClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(200);

        $this->assertJsonContains([
            '@context' => '/api/contexts/News',
            '@id' => '/api/news/'.$id,
            'publicationStatus' => 'published',
        ]);
    }

    public function testPublishedNewsValidateAsAdmin(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Bocciardo, doppio oro alle Paralimpiadi',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/validate';

        $response = static::adminClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(200);

        $this->assertJsonContains([
            '@context' => '/api/contexts/News',
            '@id' => '/api/news/'.$id,
            'publicationStatus' => 'published',
        ]);
    }
}
