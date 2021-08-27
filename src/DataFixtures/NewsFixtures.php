<?php

namespace App\DataFixtures;

use App\Entity\News;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class NewsFixtures extends Fixture implements DependentFixtureInterface
{
    public const RONALDO_NEWS_REFERENCE = 'ronaldo';
    public const TERREMOTO_NEWS_REFERENCE = 'terremoto';
    public const YOUTOBO_NEWS_REFERENCE = 'youtobo';
    public const BOCCIARDO_NEWS_REFERENCE = 'bocciardo';
    public const DEUTSCHE_NEWS_REFERENCE = 'deutsche';

    public function load(ObjectManager $manager)
    {
        $this->ronaldoNewsBuilder($manager);
        $this->terremotoNewsBuilder($manager);
        $this->youtoboNewsBuilder($manager);
        $this->bocciardoNewsBuilder($manager);
        $this->deutscheNewsBuilder($manager);

        $manager->flush();
    }

    public function ronaldoNewsBuilder(ObjectManager $manager)
    {
        $news = new News();
        $news->setTitle('Cristiano Ronaldo, l\'addio alla Juventus è sempre più vicino');
        $news->setDescription('A riportarlo è la Gazzetta dello Sport. La società di Agnelli ha chiesto 25 milioni, la cifra dell\'ammortamento di CR7. Fonti vicine al calciatore parlano di un biennale a cifre inferiori rispetto ai 31 milioni elargiti dai bianconeri');
        $news->setCategory($this->getReference(CategoryFixtures::PRIMA_PAGINA_CATEGORY_REFERENCE));
        $news->addTag($this->getReference(TagFixtures::SPORT_TAG_REFERENCE));
        $news->addTag($this->getReference(TagFixtures::ECONOMIA_TAG_REFERENCE));
        $news->setPublicationStatus('idea');
        $manager->persist($news);

        $this->addReference(self::RONALDO_NEWS_REFERENCE, $news);
    }

    public function terremotoNewsBuilder(ObjectManager $manager)
    {
        $news = new News();
        $news->setTitle('Terremoto 2016, molto è ancora fermo sul recupero dei centri storici');
        $news->setDescription('Sono passati ben cinque anni dagli eventi sismici che colpirono, il 24 agosto 2016 e nei mesi seguenti');
        $news->setCategory($this->getReference(CategoryFixtures::LETTERE_AL_DIRETTORE_CATEGORY_REFERENCE));
        $news->addTag($this->getReference(TagFixtures::CRONACA_TAG_REFERENCE));
        $news->setPublicationStatus('draft');
        $manager->persist($news);

        $this->addReference(self::TERREMOTO_NEWS_REFERENCE, $news);
    }

    public function youtoboNewsBuilder(ObjectManager $manager)
    {
        $news = new News();
        $news->setTitle('Youtubo anche io, non solo ‘mukbang’: nei suoi video c’era molto di più');
        $news->setDescription('C’era questo ragazzo calabrese, anzi un omone, nella sua casetta, sul suo tavolo della cucina, la sua acqua minerale sempre a portata di mano, il suo bicchiere, la telecamera fissa, un intro diventato un marchio');
        $news->setCategory($this->getReference(CategoryFixtures::LETTERE_AL_DIRETTORE_CATEGORY_REFERENCE));
        $news->addTag($this->getReference(TagFixtures::CRONACA_TAG_REFERENCE));
        $news->setPublicationStatus('ready_to_publish');
        $manager->persist($news);

        $this->addReference(self::YOUTOBO_NEWS_REFERENCE, $news);
    }

    public function bocciardoNewsBuilder(ObjectManager $manager)
    {
        $news = new News();
        $news->setTitle('Bocciardo, doppio oro alle Paralimpiadi');
        $news->setDescription('Classe 1994, affetto dalla nascita da diplegia spastica - non muove gli arti inferiori - il nuotatore genovese è stato il primo multi-medagliato dei Giochi 2021:');
        $news->setCategory($this->getReference(CategoryFixtures::EDITORIALE_CATEGORY_REFERENCE));
        $news->addTag($this->getReference(TagFixtures::SPORT_TAG_REFERENCE));
        $news->setPublicationStatus('published');
        $manager->persist($news);

        $this->addReference(self::BOCCIARDO_NEWS_REFERENCE, $news);
    }

    public function deutscheNewsBuilder(ObjectManager $manager)
    {
        $news = new News();
        $news->setTitle('Deutsche Bank, indagini in Germania e Usa sui prodotti di investimento sostenibile');
        $news->setDescription('La banca non commenta le indagini ma ricorda un impegno ventennale nel settore.');
        $news->setCategory($this->getReference(CategoryFixtures::PRIMA_PAGINA_CATEGORY_REFERENCE));
        $news->addTag($this->getReference(TagFixtures::ECONOMIA_TAG_REFERENCE));
        $news->addTag($this->getReference(TagFixtures::CRONACA_TAG_REFERENCE));
        $news->setPublicationStatus('unpublished');
        $manager->persist($news);

        $this->addReference(self::DEUTSCHE_NEWS_REFERENCE, $news);
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class,
            TagFixtures::class,
        ];
    }
}
