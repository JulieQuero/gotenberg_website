<?php

namespace App\DataFixtures;

use App\Entity\Subscription;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SubscriptionFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Creating Freemium subscription
        $freemium = new Subscription();
        $freemium->setTitle('Freemium');
        $freemium->setDescription('Basic subscription with limited features');
        $freemium->setPdfLimit(2);
        $freemium->setPrice(0);
        $manager->persist($freemium);

        // Creating Prime subscription
        $prime = new Subscription();
        $prime->setTitle('Prime');
        $prime->setDescription('Premium subscription with unlimited features');
        $prime->setPdfLimit(1000);
        $prime->setPrice(10.99);
        $manager->persist($prime);

        // Persisting the subscriptions
        $manager->flush();
    }
}
