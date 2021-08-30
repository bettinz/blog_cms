<?php

namespace App\Tests\Functional\ChangeNewsPublicationStatus\ReadyToPublish;

use App\Entity\News;
use App\Tests\Functional\CommonFunctions;
use Doctrine\ORM\EntityManager;

class ReadyToPublishNewsMoveToDraftsTest extends CommonFunctions
{
    private EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = CommonFunctions::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testReadyToPublishNewsMoveToDraftsAsEditor(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Youtubo anche io, non solo ‘mukbang’: nei suoi video c’era molto di più',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/move-to-drafts';

        $response = static::editorClient()->request('GET', $url);

        $this->assertResponseStatusCodeSame(403);
    }

    public function testReadyToPublishNewsMoveToDraftsAsPublisher(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Youtubo anche io, non solo ‘mukbang’: nei suoi video c’era molto di più',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/move-to-drafts';

        $response = static::publisherClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(200);

        $this->assertJsonContains([
            '@context' => '/api/contexts/News',
            '@id' => '/api/news/'.$id,
            'publicationStatus' => 'draft',
        ]);
        $news->setPublicationStatus('draft');
        $this->entityManager->flush();
    }

    public function testReadyToPublishNewsMoveToDraftsAsReviewer(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Youtubo anche io, non solo ‘mukbang’: nei suoi video c’era molto di più',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/move-to-drafts';

        $response = static::reviewerClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(403);
    }

    public function testReadyToPublishNewsMoveToDraftsAsAdmin(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Youtubo anche io, non solo ‘mukbang’: nei suoi video c’era molto di più',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/move-to-drafts';

        $response = static::adminClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(200);

        $this->assertJsonContains([
            '@context' => '/api/contexts/News',
            '@id' => '/api/news/'.$id,
            'publicationStatus' => 'draft',
        ]);
        $news->setPublicationStatus('ready_to_publish');
        $this->entityManager->flush();
    }
}
