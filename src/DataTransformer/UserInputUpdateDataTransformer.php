<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\DTO\UserInputUpdateDto;
use App\Entity\User;
use App\Service\TransformUserInputDtoToUser;

class UserInputUpdateDataTransformer implements DataTransformerInterface
{
    private ValidatorInterface $validator;
    private TransformUserInputDtoToUser $dtoToUser;

    public function __construct(ValidatorInterface $validator, TransformUserInputDtoToUser $dtoToUser)
    {
        $this->validator = $validator;
        $this->dtoToUser = $dtoToUser;
    }

    public function transform($object, string $to, array $context = []): User
    {
        $this->validator->validate($object);

        return $this->dtoToUser->transformExisting($object, $context[AbstractItemNormalizer::OBJECT_TO_POPULATE]);
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof User) {
            // already transformed
            return false;
        }

        return User::class === $to && ($context['input']['class'] ?? null) === UserInputUpdateDto::class;
    }
}
