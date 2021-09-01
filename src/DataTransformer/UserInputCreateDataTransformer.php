<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\DTO\UserInputCreateDto;
use App\Entity\User;
use App\Service\TransformUserInputDtoToUser;

class UserInputCreateDataTransformer implements DataTransformerInterface
{
    private ValidatorInterface $validator;
    private TransformUserInputDtoToUser $dtoToUser;

    public function __construct(ValidatorInterface $validator, TransformUserInputDtoToUser $dtoToUser)
    {
        $this->validator = $validator;
        $this->dtoToUser = $dtoToUser;
    }

    public function transform($object, string $to, array $context = [])
    {
        $this->validator->validate($object);

        return $this->dtoToUser->transform($object);
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof User) {
            // already transformed
            return false;
        }

        return User::class === $to && ($context['input']['class'] ?? null) === UserInputCreateDto::class;
    }
}
