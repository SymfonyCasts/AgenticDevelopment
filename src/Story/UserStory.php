<?php

namespace App\Story;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Zenstruck\Foundry\Attribute\AsFixture;
use Zenstruck\Foundry\Story;

use function Zenstruck\Foundry\Persistence\persist;

#[AsFixture(name: 'users', groups: ['dev'])]
final class UserStory extends Story
{
    private const string ADMIN_EMAIL = 'the.curator@lost-and-found.time';
    private const string PASSWORD = 'tardis';

    public function __construct(
        private readonly PasswordHasherFactoryInterface $passwordHasherFactory,
    ) {
    }

    public function build(): void
    {
        $this->addState('admin', persist(User::class, [
            'email' => self::ADMIN_EMAIL,
            'roles' => ['ROLE_ADMIN'],
            'password' => $this->passwordHasherFactory->getPasswordHasher(User::class)->hash(self::PASSWORD),
        ]));
    }
}
