<?php

namespace App\Tests\Functional\ChangeNewsPublicationStatus\Unpublished;

use App\Entity\News;
use App\Tests\Functional\CommonFunctions;
use Doctrine\ORM\EntityManager;

class UnpublishedNewsPublishTest extends CommonFunctions
{
    private EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = CommonFunctions::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testUnpublishedNewsPublishAsEditor(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Deutsche Bank, indagini in Germania e Usa sui prodotti di investimento sostenibile',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/publish';

        $response = static::editorClient()->request('GET', $url);

        $this->assertResponseStatusCodeSame(403);
    }

    public function testUnpublishedNewsPublishAsPublisher(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Deutsche Bank, indagini in Germania e Usa sui prodotti di investimento sostenibile',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/publish';

        $response = static::publisherClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(200);

        $this->assertJsonContains([
            '@context' => '/api/contexts/News',
            '@id' => '/api/news/'.$id,
            'publicationStatus' => 'published',
        ]);
        $news->setPublicationStatus('unpublished');
        $this->entityManager->flush();
    }

    public function testUnpublishedNewsPublishAsReviewer(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Deutsche Bank, indagini in Germania e Usa sui prodotti di investimento sostenibile',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/publish';

        $response = static::reviewerClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(403);
    }

    public function testUnpublishedNewsPublishAsAdmin(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Deutsche Bank, indagini in Germania e Usa sui prodotti di investimento sostenibile',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/publish';

        $response = static::adminClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(200);

        $this->assertJsonContains([
            '@context' => '/api/contexts/News',
            '@id' => '/api/news/'.$id,
            'publicationStatus' => 'published',
        ]);
        $news->setPublicationStatus('unpublished');
        $this->entityManager->flush();
    }
}
