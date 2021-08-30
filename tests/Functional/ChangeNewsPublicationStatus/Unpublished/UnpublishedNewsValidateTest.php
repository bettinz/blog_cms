<?php

namespace App\Tests\Functional\ChangeNewsPublicationStatus\Unpublished;

use App\Entity\News;
use App\Tests\Functional\CommonFunctions;
use Doctrine\ORM\EntityManager;

class UnpublishedNewsValidateTest extends CommonFunctions
{
    private EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = CommonFunctions::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testUnpublishedNewsValidateAsEditor(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Deutsche Bank, indagini in Germania e Usa sui prodotti di investimento sostenibile',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/validate';

        $response = static::editorClient()->request('GET', $url);

        $this->assertResponseStatusCodeSame(200);

        $this->assertJsonContains([
            '@context' => '/api/contexts/News',
            '@id' => '/api/news/'.$id,
            'publicationStatus' => 'unpublished',
        ]);
    }

    public function testUnpublishedNewsValidateAsPublisher(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Deutsche Bank, indagini in Germania e Usa sui prodotti di investimento sostenibile',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/validate';

        $response = static::publisherClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(403);
    }

    public function testUnpublishedNewsValidateAsReviewer(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Deutsche Bank, indagini in Germania e Usa sui prodotti di investimento sostenibile',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/validate';

        $response = static::reviewerClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(200);

        $this->assertJsonContains([
            '@context' => '/api/contexts/News',
            '@id' => '/api/news/'.$id,
            'publicationStatus' => 'unpublished',
        ]);
    }

    public function testUnpublishedNewsValidateAsAdmin(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Deutsche Bank, indagini in Germania e Usa sui prodotti di investimento sostenibile',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/validate';

        $response = static::adminClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(200);

        $this->assertJsonContains([
            '@context' => '/api/contexts/News',
            '@id' => '/api/news/'.$id,
            'publicationStatus' => 'unpublished',
        ]);
    }
}
