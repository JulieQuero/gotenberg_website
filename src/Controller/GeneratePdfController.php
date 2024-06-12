<?php

namespace App\Controller;

use App\Entity\Pdf;
use App\Service\GotenbergServiceCall;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GeneratePdfController extends AbstractController
{

    public function __construct(
        private GotenbergServiceCall $pdfService,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @throws \JsonException
     */
    #[Route('/generate/pdf/', name: 'app_generate_pdf')]

    public function generatePdf(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $this->logger->info('Entrée dans la méthode generatePdf()');
        $form = $this->createFormBuilder()
            ->add('url', null, ['required' => true])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->logger->info('Le formulaire a été soumis avec succès');

            $url = $form->getData()['url'];

            $responseJson = $this->pdfService->generatePdfFromUrl($url);
            $responseData = json_decode($responseJson, true, 512, JSON_THROW_ON_ERROR);
            $pdfFilePath = $responseData['pdf_content'];

            $this->logger->info('Le fichier PDF a été généré avec succès : ' . $pdfFilePath);

            $pdfUrl = $_ENV['MICROSERVICE_URL'] . '/' . $pdfFilePath;


            $tempFilePath = tempnam(sys_get_temp_dir(), 'pdf_');
            $pdfData = file_get_contents($pdfUrl);
            file_put_contents($tempFilePath, $pdfData);

            if ($pdfData !== false) {

                $response = new Response($pdfData);

                $currentDateTime = new \DateTime();
                $response->headers->set('Content-Disposition', 'attachment; filename=pdf_'.$currentDateTime->format('Y-m-d_H:i:s').'.pdf');

                register_shutdown_function(function() use ($tempFilePath) {
                    if (file_exists($tempFilePath)) {
                        unlink($tempFilePath);
                    }
                });

                $pdf = new Pdf();
                $pdf->setTitle('pdf_'.$currentDateTime->format('Y-m-d_H:i:s').'.pdf');
                $pdf->setCreatedAt(new \DateTimeImmutable());
                $pdf->setUser($user);

                $user->addPdf($pdf);

                $entityManager->persist($pdf);
                $entityManager->persist($user);

                $entityManager->flush();
            }

            return $this->redirectToRoute('app_generate_pdf');
        }

        return $this->render('generate_pdf/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}