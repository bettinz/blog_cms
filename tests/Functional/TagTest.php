<?php

namespace App\Tests\Functional;

use App\Entity\Tag;

class TagTest extends CommonFunctions
{
    public const TAG_COLLECTION_PATH = '/api/tags';
    public const TAG_CLASS_NAME = Tag::class;

    public function testGetTagCollectionUnlogged(): void
    {
        $response = static::unloggedClient()->request('GET', self::TAG_COLLECTION_PATH);

        $this->assertResponseIsSuccessful();

        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/api/contexts/Tag',
            '@id' => '/api/tags',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 3,
        ]);

        $this->assertCount(3, $response->toArray()['hydra:member']);

        $this->assertMatchesResourceCollectionJsonSchema(self::TAG_CLASS_NAME);
    }

    public function testGetTagItemUnlogged(): void
    {
        $iri = $this->findIriBy(Tag::class, ['name' => 'cronaca']);

        $response = static::unloggedClient()->request('GET', $iri);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/api/contexts/Tag',
            '@id' => $iri,
            '@type' => 'Tag',
        ]);
    }

    public function testCreateTagItemUnlogged(): void
    {
        $response = static::unloggedClient()->request('POST', self::TAG_COLLECTION_PATH, [
            'json' => [
                'name' => 'finanza',
            ],
        ]);

        $this->assertResponseStatusCodeSame(401);
    }

    public function testCreateTagItemWithoutName(): void
    {
        $response = static::adminClient()->request('POST', self::TAG_COLLECTION_PATH, [
            'json' => [],
        ]);

        $this->assertResponseStatusCodeSame(422);
        $numberOfViolatons = 1;
        $this->assertEquals($numberOfViolatons, count($response->toArray(false)['violations']));
    }

    public function testCreateTagItemWithEmptyName(): void
    {
        $response = static::adminClient()->request('POST', self::TAG_COLLECTION_PATH, [
            'json' => [
                'name' => '',
            ],
        ]);

        $this->assertResponseStatusCodeSame(422);
        $numberOfViolatons = 1;
        $this->assertEquals($numberOfViolatons, count($response->toArray(false)['violations']));
    }

    public function testCreateTagItemAsEditor(): void
    {
        $response = static::editorClient()->request('POST', self::TAG_COLLECTION_PATH, [
            'json' => [
                'name' => 'finanza',
            ],
        ]);

        $this->assertResponseStatusCodeSame(403);
    }

    public function testCreateTagItemAsPublisher(): void
    {
        $response = static::editorClient()->request('POST', self::TAG_COLLECTION_PATH, [
            'json' => [
                'name' => 'finanza',
            ],
        ]);

        $this->assertResponseStatusCodeSame(403);
    }

    public function testCreateTagItemAsReviewer(): void
    {
        $response = static::editorClient()->request('POST', self::TAG_COLLECTION_PATH, [
            'json' => [
                'name' => 'finanza',
            ],
        ]);

        $this->assertResponseStatusCodeSame(403);
    }

    public function testCreateTagItemAsAdmin(): void
    {
        $response = static::adminClient()->request('POST', self::TAG_COLLECTION_PATH, [
            'json' => [
                'name' => 'finanza',
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);
    }
}
