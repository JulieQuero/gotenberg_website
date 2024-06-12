<?php
// tests/Entity/UserTest.php
namespace App\Tests\Entity;

use App\Entity\Subscription;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testGetterAndSetter(): void
    {
        // Création d'une instance de l'entité User
        $user = new User();

        // Définition de données de test
        $email = 'test@test.com';
        $lastname = 'Doe';
        $firstname = 'John';
        $password = 'password';
        $role = 'ROLE_USER';
        $createdAt = new \DateTimeImmutable();
        $updatedAt = new \DateTimeImmutable();
        $subscriptionId = new Subscription();
        $subscriptionEndAt = new \DateTimeImmutable();


        // Utilisation des setters
        $user->setEmail($email);
        $user->setLastname($lastname);
        $user->setFirstname($firstname);
        $user->setPassword($password);
        $user->setRole($role);
        $user->setCreatedAt($createdAt);
        $user->setUpdatedAt($updatedAt);
        $user->setSubscription($subscriptionId);
        $user->setSubscriptionEndAt($subscriptionEndAt);

        // Vérification des getters
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($lastname, $user->getLastname());
        $this->assertEquals($firstname, $user->getFirstname());
        $this->assertEquals($password, $user->getPassword());
        $this->assertEquals($role, $user->getRole());
        $this->assertEquals($createdAt, $user->getCreatedAt());
        $this->assertEquals($updatedAt, $user->getUpdatedAt());
        $this->assertEquals($subscriptionId, $user->getSubscription());
        $this->assertEquals($subscriptionEndAt, $user->getSubscriptionEndAt());

    }
}