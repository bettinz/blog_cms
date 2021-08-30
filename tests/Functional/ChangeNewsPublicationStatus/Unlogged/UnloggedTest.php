<?php

namespace App\Tests\Functional\ChangeNewsPublicationStatus\Unlogged;

use App\Entity\News;
use App\Tests\Functional\CommonFunctions;
use Doctrine\ORM\EntityManager;

class UnloggedTest extends CommonFunctions
{
    private EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = CommonFunctions::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testNewsMoveToDraftsUnlogged(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Bocciardo, doppio oro alle Paralimpiadi',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/move-to-drafts';

        $response = static::unloggedClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(401);
    }

    public function testNewsPrePublicateUnlogged(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Bocciardo, doppio oro alle Paralimpiadi',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/pre-publicate';

        $response = static::unloggedClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(401);
    }

    public function testNewsPublishUnlogged(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Bocciardo, doppio oro alle Paralimpiadi',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/publish';

        $response = static::unloggedClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(401);
    }

    public function testNewsUnpublishUnlogged(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Bocciardo, doppio oro alle Paralimpiadi',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/unpublish';

        $response = static::unloggedClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(401);
    }

    public function testNewsInvalidateUnlogged(): void
    {
        /**
         * @var News|null $news
         */
        $news = $this->entityManager->getRepository('App:News')->findOneBy([
            'title' => 'Bocciardo, doppio oro alle Paralimpiadi',
        ]);

        $id = $news->getId();

        $url = '/api/news/'.$id.'/invalidate';

        $response = static::unloggedClient()->request('GET', $url);
        $this->assertResponseStatusCodeSame(401);
    }
}
