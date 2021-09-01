<?php

namespace App\Tests\Functional\ChangeNewsPublicationStatus\Idea;

use App\Entity\News;
use App\Tests\Functional\CommonFunctions;
use Doctrine\ORM\EntityManager;

class IdeaNewsMoveToDraftsTest extends CommonFunctions
{
    private EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = CommonFunctions::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testIdeaNewsMoveToDraftsAsEditor(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Cristiano Ronaldo, l\'addio alla Juventus è sempre più vicino',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/move-to-drafts';

        $response = static::editorClient()->request('GET', $url);

        $this->assertResponseStatusCodeSame(403);
    }

    public function testIdeaNewsMoveToDraftsAsPublisher(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Cristiano Ronaldo, l\'addio alla Juventus è sempre più vicino',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/move-to-drafts';

        $response = static::publisherClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(200);

        $this->assertJsonContains([
            '@context' => '/api/contexts/News',
            '@id' => '/api/news/'.$id,
            'publicationStatus' => 'idea',
        ]);
    }

    public function testIdeaNewsMoveToDraftsAsReviewer(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Cristiano Ronaldo, l\'addio alla Juventus è sempre più vicino',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/move-to-drafts';

        $response = static::reviewerClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(403);
    }

    public function testIdeaNewsMoveToDraftsAsAdmin(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Cristiano Ronaldo, l\'addio alla Juventus è sempre più vicino',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/move-to-drafts';

        $response = static::adminClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(200);

        $this->assertJsonContains([
            '@context' => '/api/contexts/News',
            '@id' => '/api/news/'.$id,
            'publicationStatus' => 'idea',
        ]);
    }
}
