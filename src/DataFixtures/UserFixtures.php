<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const EDITOR_USER_REFERENCE = 'editor';
    public const REVIEWER_USER_REFERENCE = 'reviewer';
    public const PUBLISHER_USER_REFERENCE = 'publisher';
    public const ADMIN_USER_REFERENCE = 'admin';


    private UserPasswordHasherInterface $hasher;

    /**
     * @param UserPasswordHasherInterface $hasher
     */
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $this->editorUserBuilder($manager);
        $this->reviewerUserBuilder($manager);
        $this->publisherUserBuilder($manager);
        $this->adminUserBuilder($manager);

        $manager->flush();
    }

    public function editorUserBuilder(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('editor@blog.com');
        $user->setPassword($this->hasher->hashPassword($user, 'editor@blog.com'));
        $user->setRoles(['ROLE_EDITOR']);
        $manager->persist($user);

        $this->addReference(self::EDITOR_USER_REFERENCE, $user);
    }

    public function reviewerUserBuilder(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('reviewer@blog.com');
        $user->setPassword($this->hasher->hashPassword($user, 'reviewer@blog.com'));
        $user->setRoles(['ROLE_REVIEWER']);
        $manager->persist($user);

        $this->addReference(self::REVIEWER_USER_REFERENCE, $user);
    }

    public function publisherUserBuilder(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('publisher@blog.com');
        $user->setPassword($this->hasher->hashPassword($user, 'publisher@blog.com'));
        $user->setRoles(['ROLE_PUBLISHER']);
        $manager->persist($user);

        $this->addReference(self::PUBLISHER_USER_REFERENCE, $user);
    }

    public function adminUserBuilder(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('admin@blog.com');
        $user->setPassword($this->hasher->hashPassword($user, 'admin@blog.com'));
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);

        $this->addReference(self::ADMIN_USER_REFERENCE, $user);
    }
}
