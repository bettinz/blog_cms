<?php

namespace App\Tests\Functional\ChangeNewsPublicationStatus\ReadyToPublish;

use App\Entity\News;
use App\Tests\Functional\CommonFunctions;
use Doctrine\ORM\EntityManager;

class ReadyToPublishNewsPrePublicateTest extends CommonFunctions
{
    private EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = CommonFunctions::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testReadyToPublishNewsPrePublicateAsEditor(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Youtubo anche io, non solo ‘mukbang’: nei suoi video c’era molto di più',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/pre-publicate';

        $response = static::editorClient()->request('GET', $url);

        $this->assertResponseStatusCodeSame(403);
    }

    public function testReadyToPublishNewsPrePublicateAsPublisher(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Youtubo anche io, non solo ‘mukbang’: nei suoi video c’era molto di più',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/pre-publicate';

        $response = static::publisherClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(403);
    }

    public function testReadyToPublishNewsPrePublicateAsReviewer(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Youtubo anche io, non solo ‘mukbang’: nei suoi video c’era molto di più',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/pre-publicate';

        $response = static::reviewerClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(200);

        $this->assertJsonContains([
            '@context' => '/api/contexts/News',
            '@id' => '/api/news/'.$id,
            'publicationStatus' => 'ready_to_publish',
        ]);
        $news->setPublicationStatus('ready_to_publish');
        $this->entityManager->flush();
    }

    public function testReadyToPublishNewsPrePublicateAsAdmin(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Youtubo anche io, non solo ‘mukbang’: nei suoi video c’era molto di più',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/pre-publicate';

        $response = static::adminClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(200);

        $this->assertJsonContains([
            '@context' => '/api/contexts/News',
            '@id' => '/api/news/'.$id,
            'publicationStatus' => 'ready_to_publish',
        ]);
        $news->setPublicationStatus('ready_to_publish');
        $this->entityManager->flush();
    }
}
