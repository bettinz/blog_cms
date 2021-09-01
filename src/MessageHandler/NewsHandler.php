<?php

namespace App\MessageHandler;

use App\Entity\Category;
use App\Entity\News;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class NewsHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke(News $news): void
    {
        $tags = [];
        $category = $this->manager->getRepository(Category::class)->find($news->getCategory());
        $user = $this->manager->getRepository(User::class)->find($news->getAuthor());

        foreach ($news->getTags() as $tag) {
            array_push($tags, $this->manager->getRepository(Tag::class)->find($tag));
            $news->removeTag($tag);
        }

        foreach ($tags as $tag) {
            $news->addTag($tag);
        }

        $news->setCategory($category);
        $news->setAuthor($user);

        $this->manager->persist($news);
        $this->manager->flush();
    }
}
