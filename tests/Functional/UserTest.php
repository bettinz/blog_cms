<?php

namespace App\Tests\Functional;

use App\Entity\User;

class UserTest extends CommonFunctions
{
    public const USER_COLLECTION_PATH = '/api/users';
    public const USER_CLASS_NAME = User::class;

    public function testGetUsersCollectionUnlogged(): void
    {
        $response = static::unloggedClient()->request('GET', self::USER_COLLECTION_PATH);

        $this->assertResponseStatusCodeSame(401);
    }

    public function testGetUsersCollectionAsEditor(): void
    {
        $response = static::editorClient()->request('GET', self::USER_COLLECTION_PATH);

        $this->assertResponseStatusCodeSame(403);
    }

    public function testGetUsersCollectionAsReviewer(): void
    {
        $response = static::reviewerClient()->request('GET', self::USER_COLLECTION_PATH);

        $this->assertResponseStatusCodeSame(403);
    }

    public function testGetUsersCollectionAsPublisher(): void
    {
        $response = static::publisherClient()->request('GET', self::USER_COLLECTION_PATH);

        $this->assertResponseStatusCodeSame(403);
    }

    public function testGetUsersCollectionAsAdmin(): void
    {
        $response = static::adminClient()->request('GET', self::USER_COLLECTION_PATH);

        $this->assertResponseIsSuccessful();

        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/api/contexts/User',
            '@id' => '/api/users',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 4,
        ]);

        $this->assertCount(4, $response->toArray()['hydra:member']);

        $this->assertMatchesResourceCollectionJsonSchema(self::USER_CLASS_NAME);
    }

    public function testGetUserItemUnlogged(): void
    {
        $userIri = $this->findIriBy(User::class, ['email' => 'editor@blog.com']);

        $response = static::unloggedClient()->request('GET', $userIri);

        $this->assertResponseStatusCodeSame(401);
    }

    public function testGetUserItemAsEditor(): void
    {
        $userIri = $this->findIriBy(User::class, ['email' => 'editor@blog.com']);

        $response = static::editorClient()->request('GET', $userIri);

        $this->assertResponseStatusCodeSame(403);
    }

    public function testGetUserItemAsReviewer(): void
    {
        $userIri = $this->findIriBy(User::class, ['email' => 'editor@blog.com']);

        $response = static::reviewerClient()->request('GET', $userIri);

        $this->assertResponseStatusCodeSame(403);
    }

    public function testGetUserItemAsPublisher(): void
    {
        $userIri = $this->findIriBy(User::class, ['email' => 'editor@blog.com']);

        $response = static::publisherClient()->request('GET', $userIri);

        $this->assertResponseStatusCodeSame(403);
    }

    public function testGetUserItemAsAdmin(): void
    {
        $userIri = $this->findIriBy(User::class, ['email' => 'editor@blog.com']);

        $response = static::adminClient()->request('GET', $userIri);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@id' => $userIri,
            '@type' => 'User',
        ]);
    }

    public function testCreateUserItemUnlogged(): void
    {
        $response = static::unloggedClient()->request('POST', self::USER_COLLECTION_PATH, [
            'json' => [
                'email' => 'test@email.email',
            ],
        ]);

        $this->assertResponseStatusCodeSame(401);
    }

    public function testCreateUserItemAsEditor(): void
    {
        $response = static::editorClient()->request('POST', self::USER_COLLECTION_PATH, [
            'json' => [
                'email' => 'test@email.email',
            ],
        ]);

        $this->assertResponseStatusCodeSame(403);
    }

    public function testCreateUserItemAsReviewer(): void
    {
        $response = static::reviewerClient()->request('POST', self::USER_COLLECTION_PATH, [
            'json' => [
                'email' => 'test@email.email',
            ],
        ]);

        $this->assertResponseStatusCodeSame(403);
    }

    public function testCreateUserItemAsPublisher(): void
    {
        $response = static::publisherClient()->request('POST', self::USER_COLLECTION_PATH, [
            'json' => [
                'email' => 'test@email.email',
            ],
        ]);

        $this->assertResponseStatusCodeSame(403);
    }

    public function testCreateUserItemWithoutEmail(): void
    {
        $response = static::adminClient()->request('POST', self::USER_COLLECTION_PATH, [
            'json' => [
                'password' => '123456',
                'roles' => ['ROLE_ADMIN'],
            ],
        ]);

        $this->assertResponseStatusCodeSame(422);
        $numberOfViolatons = 1;
        $this->assertEquals($numberOfViolatons, count($response->toArray(false)['violations']));
    }

    public function testCreateUserItemWithEmptyEmail(): void
    {
        $response = static::adminClient()->request('POST', self::USER_COLLECTION_PATH, [
            'json' => [
                'email' => '',
                'password' => '123456',
                'roles' => ['ROLE_ADMIN'],
            ],
        ]);

        $this->assertResponseStatusCodeSame(422);
        $numberOfViolatons = 1;
        $this->assertEquals($numberOfViolatons, count($response->toArray(false)['violations']));
    }

    public function testCreateUserItemWithoutPassword(): void
    {
        $response = static::adminClient()->request('POST', self::USER_COLLECTION_PATH, [
            'json' => [
                'email' => 'test@test.local',
                'roles' => ['ROLE_ADMIN'],
            ],
        ]);

        $this->assertResponseStatusCodeSame(422);
        $numberOfViolatons = 1;
        $this->assertEquals($numberOfViolatons, count($response->toArray(false)['violations']));
    }

    public function testCreateUserItemWithEmptyPassword(): void
    {
        $response = static::adminClient()->request('POST', self::USER_COLLECTION_PATH, [
            'json' => [
                'email' => 'test@test.local',
                'password' => '',
                'roles' => ['ROLE_ADMIN'],
            ],
        ]);

        $this->assertResponseStatusCodeSame(422);
        $numberOfViolatons = 1;
        $this->assertEquals($numberOfViolatons, count($response->toArray(false)['violations']));
    }

    public function testCreateUserItemWithoutRoles(): void
    {
        $response = static::adminClient()->request('POST', self::USER_COLLECTION_PATH, [
            'json' => [
                'email' => 'test@test.local',
                'password' => '123456',
            ],
        ]);

        $this->assertResponseStatusCodeSame(422);
        $numberOfViolatons = 1;
        $this->assertEquals($numberOfViolatons, count($response->toArray(false)['violations']));
    }

    public function testCreateUserItemWithEmptyRoles(): void
    {
        $response = static::adminClient()->request('POST', self::USER_COLLECTION_PATH, [
            'json' => [
                'email' => 'test@test.local',
                'password' => '123456',
                'roles' => [],
            ],
        ]);

        $this->assertResponseStatusCodeSame(422);
        $numberOfViolatons = 1;
        $this->assertEquals($numberOfViolatons, count($response->toArray(false)['violations']));
    }
}
