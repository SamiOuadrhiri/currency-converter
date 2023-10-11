<?php

namespace App\Service;

use App\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;

class LoggerService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createLogEntry($description, $sourceFile)
    {
        $logEntry = new Log();

        $dateTime = new \DateTime();
        $dateString = $dateTime->format('Y-m-d H:i:s');

        $logEntry->setDescription($description);
        $logEntry->setSourceFile($sourceFile);
        $logEntry->setDate($dateString);

        $this->entityManager->persist($logEntry);
        $this->entityManager->flush();
    }
}
