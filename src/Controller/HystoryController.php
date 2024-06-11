<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HystoryController extends AbstractController
{
    #[Route('/hystory', name: 'app_hystory')]
    public function index(): Response
    {
        return $this->render('hystory/index.html.twig', [
            'controller_name' => 'HystoryController',
        ]);
    }
}
