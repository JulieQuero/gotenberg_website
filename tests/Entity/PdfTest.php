<?php
// tests/Entity/UserTest.php
namespace App\Tests\Entity;

use App\Entity\Pdf;
use App\Entity\Subscription;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class PdfTest extends TestCase
{
    public function testGetterAndSetter(): void
    {
        // Création d'une instance de l'entité Pdf
        $pdf = new Pdf();

        // Définition de données de test
        $title = 'title';
        $created_at = new \DateTimeImmutable();
        $user_id = new User();

        // Utilisation des setters
        $pdf->setTitle($title);
        $pdf->setCreatedAt($created_at);
        $pdf->setUserId($user_id);

        // Vérification des getters
        $this->assertEquals($title, $pdf->getTitle());
        $this->assertEquals($created_at, $pdf->getCreatedAt());
        $this->assertEquals($user_id, $pdf->getUserId());

    }
}