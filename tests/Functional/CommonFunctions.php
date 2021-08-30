<?php

namespace App\Tests\Functional;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CommonFunctions extends ApiTestCase
{
    const LOGIN_PATH = '/api/login_check';

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
    }

    public function unloggedClient(array $kernelOptions = []): HttpClientInterface
    {
        return parent::createClient($kernelOptions, [
            'base_uri' => $_ENV['WEB_ADDRESS'],
        ]);
    }

    public static function editorClient(array $kernelOptions = []): HttpClientInterface
    {
        $response = static::createClient([], [
            'base_uri' => $_ENV['WEB_ADDRESS'],
        ])
            ->request(
                'POST',
                self::LOGIN_PATH,
                [
                    'json' => [
                        'username' => $_ENV['EDITOR_USERNAME'],
                        'password' => $_ENV['EDITOR_PASSWORD'],
                    ],
                ]
            );

        $token = json_decode($response->getContent(), true);

        return parent::createClient($kernelOptions, [
            'auth_bearer' => $token['token'],
            'base_uri' => $_ENV['WEB_ADDRESS'],
        ]);
    }

    public static function reviewerClient(array $kernelOptions = []): HttpClientInterface
    {
        $response = static::createClient([], [
            'base_uri' => $_ENV['WEB_ADDRESS'],
        ])
            ->request(
                'POST',
                self::LOGIN_PATH,
                [
                    'json' => [
                        'username' => $_ENV['REVIEWER_USERNAME'],
                        'password' => $_ENV['REVIEWER_PASSWORD'],
                    ],
                ]
            );

        $token = json_decode($response->getContent(), true);

        return parent::createClient($kernelOptions, [
            'auth_bearer' => $token['token'],
            'base_uri' => $_ENV['WEB_ADDRESS'],
        ]);
    }

    public static function publisherClient(array $kernelOptions = []): HttpClientInterface
    {
        $response = static::createClient([], [
            'base_uri' => $_ENV['WEB_ADDRESS'],
        ])
            ->request(
                'POST',
                self::LOGIN_PATH,
                [
                    'json' => [
                        'username' => $_ENV['PUBLISHER_USERNAME'],
                        'password' => $_ENV['PUBLISHER_PASSWORD'],
                    ],
                ]
            );

        $token = json_decode($response->getContent(), true);

        return parent::createClient($kernelOptions, [
            'auth_bearer' => $token['token'],
            'base_uri' => $_ENV['WEB_ADDRESS'],
        ]);
    }

    public static function adminClient(array $kernelOptions = []): HttpClientInterface
    {
        $response = static::createClient([], [
            'base_uri' => $_ENV['WEB_ADDRESS'],
        ])
            ->request(
                'POST',
                self::LOGIN_PATH,
                [
                    'json' => [
                        'username' => $_ENV['ADMIN_USERNAME'],
                        'password' => $_ENV['ADMIN_PASSWORD'],
                    ],
                ]
            );

        $token = json_decode($response->getContent(), true);

        return parent::createClient($kernelOptions, [
            'auth_bearer' => $token['token'],
            'base_uri' => $_ENV['WEB_ADDRESS'],
        ]);
    }
}
