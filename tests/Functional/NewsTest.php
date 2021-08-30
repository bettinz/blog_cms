<?php

namespace App\Tests\Functional;

use App\Entity\Category;
use App\Entity\News;
use App\Entity\Tag;
use App\Entity\User;

class NewsTest extends CommonFunctions
{
    public const NEWS_COLLECTION_PATH = '/api/news';
    public const NEWS_CLASS_NAME = News::class;

    public function testGetNewsCollectionUnlogged(): void
    {
        $response = static::unloggedClient()->request('GET', self::NEWS_COLLECTION_PATH);

        $this->assertResponseIsSuccessful();

        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/api/contexts/News',
            '@id' => '/api/news',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 5,
        ]);

        $this->assertCount(5, $response->toArray()['hydra:member']);

        $this->assertMatchesResourceCollectionJsonSchema(self::NEWS_CLASS_NAME);
    }

    public function testGetNewsItemUnlogged(): void
    {
        $iri = $this->findIriBy(News::class, ['title' => 'Bocciardo, doppio oro alle Paralimpiadi']);

        $response = static::unloggedClient()->request('GET', $iri);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/api/contexts/News',
            '@id' => $iri,
            '@type' => 'News',
        ]);
    }

    public function testCreateNewsItemUnlogged(): void
    {
        $response = static::unloggedClient()->request('POST', self::NEWS_COLLECTION_PATH, [
            'json' => [
                'title' => 'Titolo di prova',
            ],
        ]);

        $this->assertResponseStatusCodeSame(401);
    }

    public function testCreateNewsItemWithoutTitle(): void
    {
        $categoryIri = $this->findIriBy(Category::class, ['name' => 'editoriale']);
        $tagIri = $this->findIriBy(Tag::class, ['name' => 'sport']);
        $authorIri = $this->findIriBy(User::class, ['email' => 'editor@blog.com']);

        $response = static::editorClient()->request('POST', self::NEWS_COLLECTION_PATH, [
            'json' => [
                'description' => 'Descrizione di prova',
                'category' => $categoryIri,
                'tags' => [
                    $tagIri,
                ],
                'author' => $authorIri,
            ],
        ]);

        $this->assertResponseStatusCodeSame(422);
        $numberOfViolatons = 1;
        $this->assertEquals($numberOfViolatons, count($response->toArray(false)['violations']));
    }

    public function testCreateNewsItemWithEmptyTitle(): void
    {
        $categoryIri = $this->findIriBy(Category::class, ['name' => 'editoriale']);
        $tagIri = $this->findIriBy(Tag::class, ['name' => 'sport']);
        $authorIri = $this->findIriBy(User::class, ['email' => 'editor@blog.com']);

        $response = static::editorClient()->request('POST', self::NEWS_COLLECTION_PATH, [
            'json' => [
                'title' => '',
                'description' => 'Descrizione di prova',
                'category' => $categoryIri,
                'tags' => [
                    $tagIri,
                ],
                'author' => $authorIri,
            ],
        ]);

        $this->assertResponseStatusCodeSame(422);
        $numberOfViolatons = 1;
        $this->assertEquals($numberOfViolatons, count($response->toArray(false)['violations']));
    }

    public function testCreateNewsItemWithoutDescription(): void
    {
        $categoryIri = $this->findIriBy(Category::class, ['name' => 'editoriale']);
        $tagIri = $this->findIriBy(Tag::class, ['name' => 'sport']);
        $authorIri = $this->findIriBy(User::class, ['email' => 'editor@blog.com']);

        $response = static::editorClient()->request('POST', self::NEWS_COLLECTION_PATH, [
            'json' => [
                'title' => 'Titolo di prova',
                'category' => $categoryIri,
                'tags' => [
                    $tagIri,
                ],
                'author' => $authorIri,
            ],
        ]);

        $this->assertResponseStatusCodeSame(422);
        $numberOfViolatons = 1;
        $this->assertEquals($numberOfViolatons, count($response->toArray(false)['violations']));
    }

    public function testCreateNewsItemWithEmptyDescription(): void
    {
        $categoryIri = $this->findIriBy(Category::class, ['name' => 'editoriale']);
        $tagIri = $this->findIriBy(Tag::class, ['name' => 'sport']);
        $authorIri = $this->findIriBy(User::class, ['email' => 'editor@blog.com']);

        $response = static::editorClient()->request('POST', self::NEWS_COLLECTION_PATH, [
            'json' => [
                'title' => 'Titolo di prova',
                'description' => '',
                'category' => $categoryIri,
                'tags' => [
                    $tagIri,
                ],
                'author' => $authorIri,
            ],
        ]);

        $this->assertResponseStatusCodeSame(422);
        $numberOfViolatons = 1;
        $this->assertEquals($numberOfViolatons, count($response->toArray(false)['violations']));
    }

    public function testCreateNewsItemWithoutCategory(): void
    {
        $categoryIri = $this->findIriBy(Category::class, ['name' => 'editoriale']);
        $tagIri = $this->findIriBy(Tag::class, ['name' => 'sport']);
        $authorIri = $this->findIriBy(User::class, ['email' => 'editor@blog.com']);

        $response = static::editorClient()->request('POST', self::NEWS_COLLECTION_PATH, [
            'json' => [
                'title' => 'Titolo di prova',
                'description' => 'Descrizione di prova',
                'tags' => [
                    $tagIri,
                ],
                'author' => $authorIri,
            ],
        ]);

        $this->assertResponseStatusCodeSame(422);
        $numberOfViolatons = 1;
        $this->assertEquals($numberOfViolatons, count($response->toArray(false)['violations']));
    }

    public function testCreateNewsItemWithEmptyCategory(): void
    {
        $categoryIri = $this->findIriBy(Category::class, ['name' => 'editoriale']);
        $tagIri = $this->findIriBy(Tag::class, ['name' => 'sport']);
        $authorIri = $this->findIriBy(User::class, ['email' => 'editor@blog.com']);

        $response = static::editorClient()->request('POST', self::NEWS_COLLECTION_PATH, [
            'json' => [
                'title' => 'Titolo di prova',
                'description' => 'Descrizione di prova',
                'category' => '',
                'tags' => [
                    $tagIri,
                ],
                'author' => $authorIri,
            ],
        ]);

        $this->assertResponseStatusCodeSame(400);
    }

    public function testCreateNewsItemWithoutTags(): void
    {
        $categoryIri = $this->findIriBy(Category::class, ['name' => 'editoriale']);
        $tagIri = $this->findIriBy(Tag::class, ['name' => 'sport']);
        $authorIri = $this->findIriBy(User::class, ['email' => 'editor@blog.com']);

        $response = static::editorClient()->request('POST', self::NEWS_COLLECTION_PATH, [
            'json' => [
                'title' => 'Titolo di prova',
                'description' => 'Descrizione di prova',
                'category' => $categoryIri,
                'author' => $authorIri,
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);
    }

    public function testCreateNewsItemWithEmptyTags(): void
    {
        $categoryIri = $this->findIriBy(Category::class, ['name' => 'editoriale']);
        $tagIri = $this->findIriBy(Tag::class, ['name' => 'sport']);
        $authorIri = $this->findIriBy(User::class, ['email' => 'editor@blog.com']);

        $response = static::editorClient()->request('POST', self::NEWS_COLLECTION_PATH, [
            'json' => [
                'title' => 'Titolo di prova',
                'description' => 'Descrizione di prova',
                'category' => '',
                'tags' => [],
                'author' => $authorIri,
            ],
        ]);

        $this->assertResponseStatusCodeSame(400);
    }

    public function testCreateNewsItemWithoutAuthor(): void
    {
        $categoryIri = $this->findIriBy(Category::class, ['name' => 'editoriale']);
        $tagIri = $this->findIriBy(Tag::class, ['name' => 'sport']);
        $authorIri = $this->findIriBy(User::class, ['email' => 'editor@blog.com']);

        $response = static::editorClient()->request('POST', self::NEWS_COLLECTION_PATH, [
            'json' => [
                'title' => 'Titolo di prova',
                'description' => 'Descrizione di prova',
                'category' => $categoryIri,
                'tags' => [
                    $tagIri,
                ],
            ],
        ]);

        $this->assertResponseStatusCodeSame(422);
        $numberOfViolatons = 1;
        $this->assertEquals($numberOfViolatons, count($response->toArray(false)['violations']));
    }

    public function testCreateNewsItemWithEmptyAuthor(): void
    {
        $categoryIri = $this->findIriBy(Category::class, ['name' => 'editoriale']);
        $tagIri = $this->findIriBy(Tag::class, ['name' => 'sport']);
        $authorIri = $this->findIriBy(User::class, ['email' => 'editor@blog.com']);

        $response = static::editorClient()->request('POST', self::NEWS_COLLECTION_PATH, [
            'json' => [
                'title' => 'Titolo di prova',
                'description' => 'Descrizione di prova',
                'category' => '',
                'tags' => [],
                'author' => '',
            ],
        ]);

        $this->assertResponseStatusCodeSame(400);
    }

    public function testCreateNewsItemAsEditor(): void
    {
        $categoryIri = $this->findIriBy(Category::class, ['name' => 'editoriale']);
        $tagIri = $this->findIriBy(Tag::class, ['name' => 'sport']);
        $authorIri = $this->findIriBy(User::class, ['email' => 'editor@blog.com']);

        $response = static::editorClient()->request('POST', self::NEWS_COLLECTION_PATH, [
            'json' => [
                'title' => 'Titolo di prova',
                'description' => 'Descrizione di prova',
                'category' => $categoryIri,
                'tags' => [
                    $tagIri,
                ],
                'author' => $authorIri,
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);
    }

    public function testCreateNewsItemAsPublisher(): void
    {
        $categoryIri = $this->findIriBy(Category::class, ['name' => 'editoriale']);
        $tagIri = $this->findIriBy(Tag::class, ['name' => 'sport']);
        $authorIri = $this->findIriBy(User::class, ['email' => 'editor@blog.com']);

        $response = static::publisherClient()->request('POST', self::NEWS_COLLECTION_PATH, [
            'json' => [
                'title' => 'Titolo di prova',
                'description' => 'Descrizione di prova',
                'category' => $categoryIri,
                'tags' => [
                    $tagIri,
                ],
                'author' => $authorIri,
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);
    }

    public function testCreateNewsItemAsReviewer(): void
    {
        $categoryIri = $this->findIriBy(Category::class, ['name' => 'editoriale']);
        $tagIri = $this->findIriBy(Tag::class, ['name' => 'sport']);
        $authorIri = $this->findIriBy(User::class, ['email' => 'editor@blog.com']);

        $response = static::reviewerClient()->request('POST', self::NEWS_COLLECTION_PATH, [
            'json' => [
                'title' => 'Titolo di prova',
                'description' => 'Descrizione di prova',
                'category' => $categoryIri,
                'tags' => [
                    $tagIri,
                ],
                'author' => $authorIri,
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);
    }

    public function testCreateNewsItemAsAdmin(): void
    {
        $categoryIri = $this->findIriBy(Category::class, ['name' => 'editoriale']);
        $tagIri = $this->findIriBy(Tag::class, ['name' => 'sport']);
        $authorIri = $this->findIriBy(User::class, ['email' => 'editor@blog.com']);

        $response = static::adminClient()->request('POST', self::NEWS_COLLECTION_PATH, [
            'json' => [
                'title' => 'Titolo di prova',
                'description' => 'Descrizione di prova',
                'category' => $categoryIri,
                'tags' => [
                    $tagIri,
                ],
                'author' => $authorIri,
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);
    }
}
