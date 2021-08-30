<?php

namespace App\Tests\Functional\ChangeNewsPublicationStatus;

use App\Entity\News;
use App\Tests\Functional\CommonFunctions;
use Doctrine\ORM\EntityManager;

class DraftNewsValidateTest extends CommonFunctions
{
    private EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = CommonFunctions::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testDraftNewsValidateAsEditor(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Terremoto 2016, molto è ancora fermo sul recupero dei centri storici',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/validate';

        $response = static::editorClient()->request('GET', $url);

        $this->assertResponseStatusCodeSame(200);

        $this->assertJsonContains([
            '@context' => '/api/contexts/News',
            '@id' => '/api/news/'.$id,
            'publicationStatus' => 'draft',
        ]);
    }

    public function testDraftNewsValidateAsPublisher(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Terremoto 2016, molto è ancora fermo sul recupero dei centri storici',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/validate';

        $response = static::publisherClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(403);
    }

    public function testDraftNewsValidateAsReviewer(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Terremoto 2016, molto è ancora fermo sul recupero dei centri storici',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/validate';

        $response = static::reviewerClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(200);

        $this->assertJsonContains([
            '@context' => '/api/contexts/News',
            '@id' => '/api/news/'.$id,
            'publicationStatus' => 'draft',
        ]);
    }

    public function testDraftNewsValidateAsAdmin(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Terremoto 2016, molto è ancora fermo sul recupero dei centri storici',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/validate';

        $response = static::adminClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(200);

        $this->assertJsonContains([
            '@context' => '/api/contexts/News',
            '@id' => '/api/news/'.$id,
            'publicationStatus' => 'draft',
        ]);
    }
}
