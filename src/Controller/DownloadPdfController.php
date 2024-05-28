<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DownloadPdfController extends AbstractController
{
    #[Route('/download/pdf', name: 'app_download_pdf')]
    public function index(): Response
    {
        return $this->render('download_pdf/index.html.twig', [
            'controller_name' => 'DownloadPdfController',
        ]);
    }
}
