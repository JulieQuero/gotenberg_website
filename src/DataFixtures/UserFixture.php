<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Subscription;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class UserFixture extends Fixture
{

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager)
    {
        // Load the subscription types
        $freemiumSubscription = $manager->getRepository(Subscription::class)->findOneBy(['title' => 'Freemium']);
        $primeSubscription = $manager->getRepository(Subscription::class)->findOneBy(['title' => 'Prime']);

        // Creating users
        for ($i = 1; $i <= 3; $i++) {
            $user = new User();
            $user->setEmail("user$i@example.com");
            $user->setLastname("Lastname$i");
            $user->setFirstname("Firstname$i");
            $user->setRole('ROLE_USER');
            $user->setCreatedAt(new \DateTimeImmutable());
            $user->setUpdatedAt(new \DateTimeImmutable());
            $user->setSubscription($freemiumSubscription);
            $user->setSubscriptionEndAt(new \DateTimeImmutable('+1 month')); // Adjust subscription end date accordingly
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $manager->persist($user);
        }

        // Creating a Prime user
        $primeUser = new User();
        $primeUser->setEmail("prime@example.com");
        $primeUser->setLastname("Prime");
        $primeUser->setFirstname("User");
        $primeUser->setRole('ROLE_USER');
        $primeUser->setCreatedAt(new \DateTimeImmutable());
        $primeUser->setUpdatedAt(new \DateTimeImmutable());
        $primeUser->setSubscription($primeSubscription);
        $primeUser->setSubscriptionEndAt(new \DateTimeImmutable('+1 year')); // Adjust subscription end date accordingly
        $primeUser->setPassword($this->passwordHasher->hashPassword($primeUser, 'password'));
        $manager->persist($primeUser);

        // Persisting the users
        $manager->flush();
    }
}
