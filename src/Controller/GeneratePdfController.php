<?php

namespace App\Controller;

use App\Entity\Pdf;
use App\Repository\PdfRepository;
use App\Service\GotenbergServiceCall;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;

class GeneratePdfController extends AbstractController
{

    public function __construct(
        private GotenbergServiceCall $pdfService,
        private LoggerInterface $logger,
        private PdfRepository $pdfEntity
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

        $UserSubscription = $user->getSubscription();
        if (
            $this->pdfEntity->countPdfGeneratedByUserOnDate(
                $user->getId(),
                new \DateTimeImmutable('today'),
                new \DateTimeImmutable('tomorrow')
            ) >= ($UserSubscription->getPdfLimit())) {
            return $this->redirectToRoute(
                'subscription_change',
                ['error' => 'Vous avez atteint votre limite de PDF générés pour aujourd\'hui. Veuillez souscrire à un abonnement supérieur pour continuer.']
            );
        }

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


            $pdfData = file_get_contents($pdfUrl);

            if ($pdfData !== false) {
                $currentDateTime = new \DateTime();

                $pdf = new Pdf();
                $pdf->setTitle('pdf_'.$currentDateTime->format('Y-m-d_H_i_s').'.pdf');
                $pdf->setUrl($pdfUrl);
                $pdf->setCreatedAt(new \DateTimeImmutable());
                $pdf->setUser($user);

                $user->addPdf($pdf);

                $entityManager->persist($pdf);
                $entityManager->persist($user);

                $entityManager->flush();

                return $this->redirectToRoute('app_download_pdf', ['id' => $pdf->getId()]);
            }

            return $this->redirectToRoute('app_generate_pdf');
        }

        return $this->render('generate_pdf/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/download/pdf/{id}', name: 'app_download_pdf')]
    public function downloadPdf(int $id, PdfRepository $pdfRepository): Response
    {
        $pdf = $pdfRepository->find($id);

        if (!$pdf) {
            throw $this->createNotFoundException('Le PDF n\'existe pas.');
        }
        $currentDateTime = new \DateTime();

        $pdfName = $pdf->getTitle();
        $pdfPath = $pdf->getUrl();

        $pdfData = file_get_contents($pdfPath);
        $response = new Response($pdfData);
        $response->headers->set('Content-Disposition', 'attachment; filename=pdf_'.$currentDateTime->format('Y-m-d_H:i:s').'.pdf');

        return $response;
    }
}