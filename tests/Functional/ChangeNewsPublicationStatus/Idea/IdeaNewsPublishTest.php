<?php

namespace App\Tests\Functional\ChangeNewsPublicationStatus\Idea;

use App\Entity\News;
use App\Tests\Functional\CommonFunctions;
use Doctrine\ORM\EntityManager;

class IdeaNewsPublishTest extends CommonFunctions
{
    private EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = CommonFunctions::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testIdeaNewsPublishAsEditor(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Cristiano Ronaldo, l\'addio alla Juventus è sempre più vicino',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/publish';

        $response = static::editorClient()->request('GET', $url);

        $this->assertResponseStatusCodeSame(403);
    }

    public function testIdeaNewsPublishAsPublisher(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Cristiano Ronaldo, l\'addio alla Juventus è sempre più vicino',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/publish';

        $response = static::publisherClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(200);

        $this->assertJsonContains([
            '@context' => '/api/contexts/News',
            '@id' => '/api/news/'.$id,
            'publicationStatus' => 'idea',
        ]);
    }

    public function testIdeaNewsPublishAsReviewer(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Cristiano Ronaldo, l\'addio alla Juventus è sempre più vicino',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/publish';

        $response = static::reviewerClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(403);
    }

    public function testIdeaNewsPublishAsAdmin(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Cristiano Ronaldo, l\'addio alla Juventus è sempre più vicino',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/publish';

        $response = static::adminClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(200);

        $this->assertJsonContains([
            '@context' => '/api/contexts/News',
            '@id' => '/api/news/'.$id,
            'publicationStatus' => 'idea',
        ]);
    }
}
