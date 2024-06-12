<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\SubscriptionRepository;
use Symfony\Component\HttpFoundation\Request;

class SubscriptionController extends AbstractController
{
    #[Route('/subscription/change', name: 'app_subscription_change')]
    public function changeSubscription(Request $request, SubscriptionRepository $subscriptionRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $subscriptions = $subscriptionRepository->findAll();

        if ($request->isMethod('POST')) {
            $subscriptionId = $request->request->get('subscriptionId');
            $subscription = $subscriptionRepository->find($subscriptionId);

            if ($subscription) {
                $user->setSubscription($subscription);
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Your subscription has been updated successfully!');
                return $this->redirectToRoute('subscription_change');
            }

            $this->addFlash('error', 'Subscription not found.');
        }

        $error = $request->query->get('error');

        return $this->render('subscription/subscription_change.html.twig', [
            'subscriptions' => $subscriptions,
            'user' => $user,
            'currentSubscription' => $user->getSubscription(),
            'error' => $error
        ]);
    }
}
