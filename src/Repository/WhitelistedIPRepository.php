<?php

namespace App\Repository;

use App\Entity\WhitelistedIP;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class WhitelistedIPRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WhitelistedIP::class);
    }

    /**
     * Controleer of het IP-adres is geautoriseerd (op de whitelist staat).
     *
     * @param string $ip Het IP-adres om te controleren.
     *
     * @return bool True als het IP-adres is geautoriseerd, anders false.
     */
    public function isIPWhitelisted(string $ip): bool
    {
        $queryBuilder = $this->createQueryBuilder('w');
        $queryBuilder
            ->select('COUNT(w.id)')
            ->where('w.IP = :ip')
            ->setParameter('ip', $ip);

        $count = $queryBuilder->getQuery()->getSingleScalarResult();

        return $count > 0;
    }
}
