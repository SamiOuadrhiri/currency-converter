<?php

namespace App\Controller;

use App\Repository\LogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LogController extends AbstractController
{
    #[Route('/log', name: 'logs')]
    public function ips(LogRepository $logRepository) {

        $logs = $logRepository->findAll();

        return $this->render('log/index.html.twig', [
            'logs' => $logs
        ]);
    }
}
