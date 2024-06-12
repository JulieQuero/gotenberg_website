<?php

namespace App\Form;

use App\Entity\Subscription;
use App\Entity\User;
use App\Repository\SubscriptionRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function __construct(
        private SubscriptionRepository $subscriptionRepository
    ) {
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class)
            ->add('lastname', TextType::class)
            ->add('firstname', TextType::class)
            ->add('password', PasswordType::class)
            ->add('subscription', EntityType::class, [
                'class' => Subscription::class,
                'choice_label' => 'title',
                'label' => 'Subscription',
                'placeholder' => 'Choose an option',
                'required' => true,
                'choices' => $this->subscriptionRepository->findAll()]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
