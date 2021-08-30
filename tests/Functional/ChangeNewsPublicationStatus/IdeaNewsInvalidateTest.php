<?php

namespace App\Tests\Functional\ChangeNewsPublicationStatus;

use App\Entity\News;
use App\Tests\Functional\CommonFunctions;

use Doctrine\ORM\EntityManager;

class IdeaNewsInvalidateTest extends CommonFunctions
{
    private EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = CommonFunctions::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testIdeaNewsInvalidateAsEditor(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Bocciardo, doppio oro alle Paralimpiadi',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/invalidate';

        $response = static::editorClient()->request('GET', $url);

        $this->assertResponseStatusCodeSame(403);
    }

    public function testIdeaNewsInvalidateAsPublisher(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Bocciardo, doppio oro alle Paralimpiadi',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/invalidate';

        $response = static::publisherClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(403);
    }

    public function testIdeaNewsInvalidateAsReviewer(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Bocciardo, doppio oro alle Paralimpiadi',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/invalidate';

        $response = static::reviewerClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(200);

        $this->assertJsonContains([
            '@context' => '/api/contexts/News',
            '@id' => '/api/news/'.$id,
            'publicationStatus' => 'idea',
        ]);
    }

    public function testIdeaNewsInvalidateAsAdmin(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Bocciardo, doppio oro alle Paralimpiadi',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/invalidate';

        $response = static::adminClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(200);

        $this->assertJsonContains([
            '@context' => '/api/contexts/News',
            '@id' => '/api/news/'.$id,
            'publicationStatus' => 'idea',
        ]);
    }
}
