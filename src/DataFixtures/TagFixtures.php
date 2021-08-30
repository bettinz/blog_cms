<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends Fixture
{
    public const CRONACA_TAG_REFERENCE = 'cronaca';
    public const SPORT_TAG_REFERENCE = 'sport';
    public const ECONOMIA_TAG_REFERENCE = 'economia';

    public function load(ObjectManager $manager)
    {
        $this->cronacaTagBuilder($manager);
        $this->sportTagBuilder($manager);
        $this->economiaTagBuilder($manager);

        $manager->flush();
    }

    public function cronacaTagBuilder(ObjectManager $manager)
    {
        $tag = new Tag();
        $tag->setName('cronaca');
        $manager->persist($tag);

        $this->addReference(self::CRONACA_TAG_REFERENCE, $tag);
    }

    public function sportTagBuilder(ObjectManager $manager)
    {
        $tag = new Tag();
        $tag->setName('sport');
        $manager->persist($tag);

        $this->addReference(self::SPORT_TAG_REFERENCE, $tag);
    }

    public function economiaTagBuilder(ObjectManager $manager)
    {
        $tag = new Tag();
        $tag->setName('economia');
        $manager->persist($tag);

        $this->addReference(self::ECONOMIA_TAG_REFERENCE, $tag);
    }
}
