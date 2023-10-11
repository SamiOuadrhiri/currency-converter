<?php

namespace App\Controller;

use App\Entity\Log;
use App\Entity\CurrencyCode;
use App\Repository\CurrencyCodeRepository;
use App\Service\LoggerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\HttpKernel\KernelInterface;

#[Route('/currency', name: 'currency.')]
class CurrencyController extends AbstractController
{
    private KernelInterface $kernel;

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, KernelInterface $kernel)
    {
        $this->entityManager = $entityManager;
        $this->kernel = $kernel;
    }

    #[Route('/', name: 'index')]
    public function index(CurrencyCodeRepository $currencyCodeRepository): Response
    {
        $currencyCodes = $currencyCodeRepository->findAll();

        return $this->render('currency/index.html.twig', [
            'currencyCodes' => $currencyCodes,
        ]);
    }

    #[Route('/codes', name: 'currency_codes')]
    public function codes(CurrencyCodeRepository $currencyCodeRepository): Response
    {
        $currencyCodes = $currencyCodeRepository->findAll();

        return $this->render('currency/codes.html.twig', [
            'currencyCodes' => $currencyCodes,
        ]);
    }

    #[Route('/create', name: 'create_currency')]
    public function create(): Response
    {
        $currencies = [
            'USD',
            'EUR',
            'GBP',
            'AUD',
            'CAD',
            'JPY',
            'CHF',
            'KMF',
            'AFN',
            'ALL',
            'DZD',
            'AOA',
            'ARS',
            'AMD',
            'AWG',
            'AZN',
            'BSD',
            'BHD',
            'BDT',
            'BBD',
            'BYR',
            'BYN',
            'BZD',
            'BOB',
            'BAM',
            'BWP',
            'BRL',
            'BND',
            'BGN',
            'BIF',
            'KHR',
            'CVE',
            'XAF',
            'XPF',
            'CLP',
            'CNY',
            'COP',
            'CDF',
            'CRC',
            'HRK',
            'CUP',
            'CZK',
            'DKK',
            'DJF',
            'DOP',
            'XCD',
            'EGP',
            'ERN',
            'ETB',
            'FJD',
            'GMD',
            'GEL',
            'GHS',
            'GIP',
            'GTQ',
            'GNF',
            'GYD',
            'HTG',
            'HNL',
            'HKD',
            'HUF',
            'ISK',
            'INR',
            'IDR',
            'IRR',
            'IQD',
            'ILS',
            'JMD',
            'JOD',
            'KZT',
            'KES',
            'KWD',
            'KGS',
            'LAK',
            'LVL',
            'LBP',
            'LSL',
            'LRD',
            'LYD',
            'LTL',
            'MOP',
            'MKD',
            'MGA',
            'MWK',
            'MYR',
            'MVR',
            'MRO',
            'MRU',
            'MUR',
            'MXN',
            'MDL',
            'MNT',
            'MAD',
            'MZN',
            'MMK',
            'NAD',
            'NPR',
            'ANG',
            'TWD',
            'TMT',
            'NZD',
            'NIO',
            'NGN',
            'NOK',
            'OMR',
            'PKR',
            'PAB',
            'PGK',
            'PYG',
            'PEN',
            'PHP',
            'PLN',
            'QAR',
            'RON',
            'RUB',
            'RWF',
            'SVC',
            'WST',
            'STN',
            'SAR',
            'RSD',
            'SCR',
            'SLL',
            'SGD',
            'SBD',
            'SOS',
            'ZAR',
            'KRW',
            'SSP',
            'LKR',
            'SDG',
            'SRD',
            'SZL',
            'SEK',
            'SYP',
            'TJS',
            'TZS',
            'THB',
            'TOP',
            'TTD',
            'TND',
            'TRY',
            'AED',
            'UGX',
            'UAH',
            'UYU',
            'UZS',
            'VUV',
            'VES',
            'VEF',
            'VND',
            'XOF',
            'YER',
            'ZMW',
        ];

        $entityManager = $this->entityManager;

        $existingCurrencyCodes = $entityManager->getRepository(CurrencyCode::class)->findAll();

        $existingCodes = [];
        foreach ($existingCurrencyCodes as $existingCode) {
            $existingCodes[] = $existingCode->getCode();
        }

        foreach ($currencies as $currencyCode) {
            if (!in_array($currencyCode, $existingCodes)) {
                $currency = new CurrencyCode();
                $currency->setCode($currencyCode);
                $entityManager->persist($currency);
            }
        }

        $entityManager->flush();

        return new Response();
    }

    #[Route('/fetch', name: 'fetch')]
    public function fetch(): Response
    {
        return $this->render('currency/fetch.html.twig');
    }

    #[Route('/data', name: 'data')]
    public function data(LoggerService $logger): Response
    {
        $entityManager = $this->entityManager;
        try {
            $application = new Application($this->kernel);
            $application->setAutoExit(false);

            $input = new ArrayInput(array(
                'command' => 'app:FetchCurrencies',
            ));

            $logger->createLogEntry('app:FetchCurrencies command gestart', __FILE__);

            $application->run($input);
        } catch (\Exception $e) {
            $logger->createLogEntry('Fout opgetreden tijdens het uitvoeren van het commando.', __FILE__);
        }


        return $this->render('currency/fetch.html.twig');
    }

}
