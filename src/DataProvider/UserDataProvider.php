<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\User;
use App\Service\UserCollectionProvider;
use App\Service\UserItemProvider;

class UserDataProvider implements ItemDataProviderInterface, CollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private UserItemProvider $userItemProvider;
    private UserCollectionProvider $userCollectionProvider;

    public function __construct(UserItemProvider $userItemProvider, UserCollectionProvider $userCollectionProvider)
    {
        $this->userItemProvider = $userItemProvider;
        $this->userCollectionProvider = $userCollectionProvider;
    }

    public function getCollection(string $resourceClass, string $operationName = null)
    {
        return $this->userCollectionProvider->provide();
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        return $this->userItemProvider->provide($id);
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return User::class === $resourceClass;
    }
}
