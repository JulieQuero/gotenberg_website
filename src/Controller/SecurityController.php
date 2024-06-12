<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\SubscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private LoggerInterface $logger
    ) {
    }

    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    public function logout():void
    {
    }

    public function createUser(Request $request, SubscriptionRepository $subscriptionRepository, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $subscriptions = $subscriptionRepository->findAll();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->logger->info('Le formulaire a été soumis avec succès');
            $user = $form->getData();
            $user->setRole('ROLE_USER');
            $user->setSubscriptionEndAt(new \DateTimeImmutable('+1 month'));
            $user->setCreatedAt(new \DateTimeImmutable());
            $user->setUpdatedAt(new \DateTimeImmutable());

            $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/create.html.twig', [
            'form' => $form->createView(),
            'subscriptions' => $subscriptions,
        ]);
    }
}