<?php

namespace App\Tests\Functional\ChangeNewsPublicationStatus\Draft;

use App\Entity\News;
use App\Tests\Functional\CommonFunctions;
use Doctrine\ORM\EntityManager;

class DraftNewsPrePublicateTest extends CommonFunctions
{
    private EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = CommonFunctions::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testDraftNewsPrePublicateAsEditor(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Terremoto 2016, molto è ancora fermo sul recupero dei centri storici',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/pre-publicate';

        $response = static::editorClient()->request('GET', $url);

        $this->assertResponseStatusCodeSame(403);
    }

    public function testDraftNewsPrePublicateAsPublisher(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Terremoto 2016, molto è ancora fermo sul recupero dei centri storici',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/pre-publicate';

        $response = static::publisherClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(403);
    }

    public function testDraftNewsPrePublicateAsReviewer(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Terremoto 2016, molto è ancora fermo sul recupero dei centri storici',
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
        $news->setPublicationStatus('draft');
        $this->entityManager->flush();
    }

    public function testDraftNewsPrePublicateAsAdmin(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Terremoto 2016, molto è ancora fermo sul recupero dei centri storici',
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
        $news->setPublicationStatus('draft');
        $this->entityManager->flush();
    }
}
