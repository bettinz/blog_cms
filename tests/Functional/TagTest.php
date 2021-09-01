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

    public function testDeleteTagUnlogged()
    {
        $iri = $this->findIriBy(Tag::class, ['name' => 'cronaca']);

        $response = static::unloggedClient()->request('DELETE', $iri);
        $this->assertResponseStatusCodeSame(401);
    }

    public function testDeleteTagAsEditor()
    {
        $iri = $this->findIriBy(Tag::class, ['name' => 'cronaca']);

        $response = static::editorClient()->request('DELETE', $iri);
        $this->assertResponseStatusCodeSame(403);
    }

    public function testDeleteTagAsPublisher()
    {
        $iri = $this->findIriBy(Tag::class, ['name' => 'cronaca']);

        $response = static::publisherClient()->request('DELETE', $iri);
        $this->assertResponseStatusCodeSame(403);
    }

    public function testDeleteTagAsReviewer()
    {
        $iri = $this->findIriBy(Tag::class, ['name' => 'cronaca']);

        $response = static::reviewerClient()->request('DELETE', $iri);
        $this->assertResponseStatusCodeSame(403);
    }

    public function testDeleteTagAsAdmin()
    {
        $iri = $this->findIriBy(Tag::class, ['name' => 'cronaca']);

        $response = static::adminClient()->request('DELETE', $iri);
        $this->assertResponseStatusCodeSame(204);

        $response = static::adminClient()->request('GET', self::TAG_COLLECTION_PATH);
        $this->assertCount(2, $response->toArray()['hydra:member']);
    }

    public function testUpdateTagUnlogged()
    {
        $iri = $this->findIriBy(Tag::class, ['name' => 'cronaca']);

        $response = static::unloggedClient()->request('PATCH', $iri);
        $this->assertResponseStatusCodeSame(401);
    }

    public function testUpdateTagAsEditor()
    {
        $iri = $this->findIriBy(Tag::class, ['name' => 'cronaca']);

        $response = static::editorClient()->request('PATCH', $iri);
        $this->assertResponseStatusCodeSame(403);
    }

    public function testUpdateTagAsPublisher()
    {
        $iri = $this->findIriBy(Tag::class, ['name' => 'cronaca']);

        $response = static::publisherClient()->request('PATCH', $iri);
        $this->assertResponseStatusCodeSame(403);
    }

    public function testUpdateTagAsReviewer()
    {
        $iri = $this->findIriBy(Tag::class, ['name' => 'cronaca']);

        $response = static::reviewerClient()->request('PATCH', $iri);
        $this->assertResponseStatusCodeSame(403);
    }

    public function testUpdateTagAsAdmin()
    {
        $iri = $this->findIriBy(Tag::class, ['name' => 'cronaca']);

        $response = static::adminClient()->request('PATCH', $iri, [
            'headers' => [
                'Content-Type' => 'application/merge-patch+json',
            ],
            'json' => [
                'name' => 'cronaca2',
            ],
        ]);

        $updatedIri = $this->findIriBy(Tag::class, ['name' => 'cronaca2']);

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/api/contexts/Tag',
            '@id' => $iri,
            '@type' => 'Tag',
        ]);
    }
}
