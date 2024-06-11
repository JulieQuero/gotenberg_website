<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SubscriptionController extends AbstractController
{
    #[Route('/subscription/change', name: 'app_subscription_change')]
    public function changeSubscription(): Response
    {
        return $this->render('subscription/subscription_change.html.twig', [
            'controller_name' => 'SubscriptionController',
        ]);
    }
}
