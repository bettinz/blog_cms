<?php

namespace App\Tests\Functional;

use App\Entity\Category;

class CategoryTest extends CommonFunctions
{
    public const CATEGORY_COLLECTION_PATH = '/api/categories';
    public const CATEGORY_CLASS_NAME = Category::class;

    public function testGetCategoryCollectionUnlogged(): void
    {
        $response = static::unloggedClient()->request('GET', self::CATEGORY_COLLECTION_PATH);

        $this->assertResponseIsSuccessful();

        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/api/contexts/Category',
            '@id' => '/api/categories',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 3,
        ]);

        $this->assertCount(3, $response->toArray()['hydra:member']);

        $this->assertMatchesResourceCollectionJsonSchema(self::CATEGORY_CLASS_NAME);
    }

    public function testGetCategoryItemUnlogged(): void
    {
        $iri = $this->findIriBy(Category::class, ['name' => 'lettere-al-direttore']);

        $response = static::unloggedClient()->request('GET', $iri);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/api/contexts/Category',
            '@id' => $iri,
            '@type' => 'Category',
        ]);
    }

    public function testCreateCategoryItemUnlogged(): void
    {
        $response = static::unloggedClient()->request('POST', self::CATEGORY_COLLECTION_PATH, [
            'json' => [
                'name' => 'cronaca-locale',
            ],
        ]);

        $this->assertResponseStatusCodeSame(401);
    }

    public function testCreateCategoryItemWithoutName(): void
    {
        $response = static::adminClient()->request('POST', self::CATEGORY_COLLECTION_PATH, [
            'json' => [],
        ]);

        $this->assertResponseStatusCodeSame(422);
        $numberOfViolatons = 1;
        $this->assertEquals($numberOfViolatons, count($response->toArray(false)['violations']));
    }

    public function testCreateCategoryItemWithEmptyName(): void
    {
        $response = static::adminClient()->request('POST', self::CATEGORY_COLLECTION_PATH, [
            'json' => [
                'name' => '',
            ],
        ]);

        $this->assertResponseStatusCodeSame(422);
        $numberOfViolatons = 1;
        $this->assertEquals($numberOfViolatons, count($response->toArray(false)['violations']));
    }

    public function testCreateCategoryItemAsEditor(): void
    {
        $response = static::editorClient()->request('POST', self::CATEGORY_COLLECTION_PATH, [
            'json' => [
                'name' => 'cronaca-locale',
            ],
        ]);

        $this->assertResponseStatusCodeSame(403);
    }

    public function testCreateCategoryItemAsPublisher(): void
    {
        $response = static::editorClient()->request('POST', self::CATEGORY_COLLECTION_PATH, [
            'json' => [
                'name' => 'cronaca-locale',
            ],
        ]);

        $this->assertResponseStatusCodeSame(403);
    }

    public function testCreateCategoryItemAsReviewer(): void
    {
        $response = static::editorClient()->request('POST', self::CATEGORY_COLLECTION_PATH, [
            'json' => [
                'name' => 'cronaca-locale',
            ],
        ]);

        $this->assertResponseStatusCodeSame(403);
    }

    public function testCreateCategoryItemAsAdmin(): void
    {
        $response = static::adminClient()->request('POST', self::CATEGORY_COLLECTION_PATH, [
            'json' => [
                'name' => 'cronaca-locale',
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);
    }

    public function testDeleteCategoryUnlogged()
    {
        $iri = $this->findIriBy(Category::class, ['name' => 'editoriale']);

        $response = static::unloggedClient()->request('DELETE', $iri);
        $this->assertResponseStatusCodeSame(401);
    }

    public function testDeleteCategoryAsEditor()
    {
        $iri = $this->findIriBy(Category::class, ['name' => 'editoriale']);

        $response = static::editorClient()->request('DELETE', $iri);
        $this->assertResponseStatusCodeSame(403);
    }

    public function testDeleteCategoryAsPublisher()
    {
        $iri = $this->findIriBy(Category::class, ['name' => 'editoriale']);

        $response = static::publisherClient()->request('DELETE', $iri);
        $this->assertResponseStatusCodeSame(403);
    }

    public function testDeleteCategoryAsReviewer()
    {
        $iri = $this->findIriBy(Category::class, ['name' => 'editoriale']);

        $response = static::reviewerClient()->request('DELETE', $iri);
        $this->assertResponseStatusCodeSame(403);
    }

    public function testDeleteCategoryAsAdmin()
    {
        $iri = $this->findIriBy(Category::class, ['name' => 'editoriale']);

        $response = static::adminClient()->request('DELETE', $iri);
        $this->assertResponseStatusCodeSame(204);

        $response = static::adminClient()->request('GET', self::CATEGORY_COLLECTION_PATH);
        $this->assertCount(2, $response->toArray()['hydra:member']);
    }

    public function testUpdateCategoryUnlogged()
    {
        $iri = $this->findIriBy(Category::class, ['name' => 'editoriale']);

        $response = static::unloggedClient()->request('PATCH', $iri);
        $this->assertResponseStatusCodeSame(401);
    }

    public function testUpdateCategoryAsEditor()
    {
        $iri = $this->findIriBy(Category::class, ['name' => 'editoriale']);

        $response = static::editorClient()->request('PATCH', $iri);
        $this->assertResponseStatusCodeSame(403);
    }

    public function testUpdateCategoryAsPublisher()
    {
        $iri = $this->findIriBy(Category::class, ['name' => 'editoriale']);

        $response = static::publisherClient()->request('PATCH', $iri);
        $this->assertResponseStatusCodeSame(403);
    }

    public function testUpdateCategoryAsReviewer()
    {
        $iri = $this->findIriBy(Category::class, ['name' => 'editoriale']);

        $response = static::reviewerClient()->request('PATCH', $iri);
        $this->assertResponseStatusCodeSame(403);
    }

    public function testUpdateCategoryAsAdmin()
    {
        $iri = $this->findIriBy(Category::class, ['name' => 'editoriale']);

        $response = static::adminClient()->request('PATCH', $iri, [
            'headers' => [
                'Content-Type' => 'application/merge-patch+json',
            ],
            'json' => [
                'name' => 'editoriale2',
            ],
        ]);

        $updatedIri = $this->findIriBy(Category::class, ['name' => 'editoriale2']);

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/api/contexts/Category',
            '@id' => $iri,
            '@type' => 'Category',
        ]);
    }
}
