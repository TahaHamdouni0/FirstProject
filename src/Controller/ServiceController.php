<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ServiceController extends AbstractController
{
    #[Route('/service/{name}', name: 'app_service_show')]
    public function showService(string $name): Response
    {
        
        return $this->render('service/index.html.twig', [
            'name' => $name,
        ]);
    }
    #[Route('/service/go-to-index', name: 'app_service_go_to_index')]
    public function goToIndex(): Response
    {
       
        return $this->redirectToRoute('app_home_index');
    }
}
