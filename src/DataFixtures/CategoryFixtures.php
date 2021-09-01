<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const PRIMA_PAGINA_CATEGORY_REFERENCE = 'prima-pagina';
    public const EDITORIALE_CATEGORY_REFERENCE = 'editoriale';
    public const LETTERE_AL_DIRETTORE_CATEGORY_REFERENCE = 'lettere-al-direttore';

    public function load(ObjectManager $manager): void
    {
        $this->primaPaginaCategoryBuilder($manager);
        $this->editorialeCategoryBuilder($manager);
        $this->lettereAlDirettoreCategoryBuilder($manager);

        $manager->flush();
    }

    public function primaPaginaCategoryBuilder(ObjectManager $manager): void
    {
        $category = new Category();
        $category->setName('prima-pagina');
        $manager->persist($category);

        $this->addReference(self::PRIMA_PAGINA_CATEGORY_REFERENCE, $category);
    }

    public function editorialeCategoryBuilder(ObjectManager $manager): void
    {
        $category = new Category();
        $category->setName('editoriale');
        $manager->persist($category);

        $this->addReference(self::EDITORIALE_CATEGORY_REFERENCE, $category);
    }

    public function lettereAlDirettoreCategoryBuilder(ObjectManager $manager): void
    {
        $category = new Category();
        $category->setName('lettere-al-direttore');
        $manager->persist($category);

        $this->addReference(self::LETTERE_AL_DIRETTORE_CATEGORY_REFERENCE, $category);
    }
}
