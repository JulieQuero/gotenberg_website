<?php
// tests/Entity/UserTest.php
namespace App\Tests\Entity;

use App\Entity\Subscription;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class SubscriptionTest extends TestCase
{
    public function testGetterAndSetter(): void
    {
        // Création d'une instance de l'entité Subscription
        $subscription = new Subscription();

        // Définition de données de test
        $title = 'title';
        $description = 'description';
        $pdf_limit = 10;
        $price = 10.0;
        $media = 'media';

        // Utilisation des setters
        $subscription->setTitle($title);
        $subscription->setDescription($description);
        $subscription->setPdfLimit($pdf_limit);
        $subscription->setPrice($price);
        $subscription->setMedia($media);

        // Vérification des getters
        $this->assertEquals($title, $subscription->getTitle());
        $this->assertEquals($description, $subscription->getDescription());
        $this->assertEquals($pdf_limit, $subscription->getPdfLimit());
        $this->assertEquals($price, $subscription->getPrice());
        $this->assertEquals($media, $subscription->getMedia());
    }
}