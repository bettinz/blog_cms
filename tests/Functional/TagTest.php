<?php

namespace App\Tests\Functional;

use App\Entity\Tag;

class TagTest extends CommonFunctions
{
    const TAG_COLLECTION_PATH = "/api/tags";
    const TAG_CLASS_NAME = Tag::class;

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

        $this->cronacaTagId = $response->toArray(true)['@id'];

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
            ]
        ]);

        $this->assertResponseStatusCodeSame(401);
    }

    public function testCreateTagItemWithoutName(): void
    {

        $response = static::unloggedClient()->request('POST', self::TAG_COLLECTION_PATH, [
            'json' => [
                'name' => 'finanza',
            ]
        ]);

        $this->assertResponseStatusCodeSame(401);
    }
}
