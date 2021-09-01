<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\DTO\UserOutputDto;
use App\Entity\User;
use App\Service\TransformUserToUserOutputDto;

class UserOutputDataTransformer implements DataTransformerInterface
{
    private TransformUserToUserOutputDto $transformUserToUserOutputDto;

    public function __construct(TransformUserToUserOutputDto $transformUserToUserOutputDto)
    {
        $this->transformUserToUserOutputDto = $transformUserToUserOutputDto;
    }

    public function transform($object, string $to, array $context = [])
    {
        return $this->transformUserToUserOutputDto->transform($object);
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return UserOutputDto::class === $to && $data instanceof User;
    }
}
