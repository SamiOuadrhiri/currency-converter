<?php

namespace App\Command;

use App\Entity\CurrencyCode;
use App\Entity\Currency;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


#[AsCommand(
    name: 'app:FetchCurrencies',
    description: 'Fetch currency data for each currency code',
)]
class FetchCurrenciesCommand extends Command
{

    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Fetch currency data for each currency code')
            ->setHelp('This command fetches currency data for each currency code and updates the Currency table.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        set_time_limit(1800);

        $entityManager = $this->entityManager;
        $currencyCodes = $entityManager->getRepository(CurrencyCode::class)->findAll();

        foreach ($currencyCodes as $currencyCode) {
            $selectedCurrency = $currencyCode->getCode();

            $json = $this->fetchJsonData($selectedCurrency);

            $existingCurrency = $entityManager->getRepository(Currency::class)->findOneBy(['codeFrom' => $selectedCurrency]);

            if ($existingCurrency) {
                foreach ($json as $data) {
                    $this->updateCurrencyData($selectedCurrency, $data);
                }
                $output->writeln('Rates data updated for ' . $selectedCurrency);
            } else {
                foreach ($json as $data) {
                    $this->createCurrencyFromJson($selectedCurrency, $data);
                }
                $output->writeln('New rates created for ' . $selectedCurrency);
            }
        }

        $entityManager->flush();

        return Command::SUCCESS;
    }

    private function fetchJsonData(string $currencyCode): array
    {
        $httpClient = HttpClient::create();

        try {
            $url = sprintf('http://www.floatrates.com/daily/%s.json', $currencyCode);
            $response = $httpClient->request('GET', $url);

            if ($response->getStatusCode() === 200) {
                $jsonData = $response->toArray();

                return $jsonData;
            } else {
                return [];
            }
        } catch (TransportExceptionInterface $e) {
            return [];
        }
    }

    private function updateCurrencyData(string $currencyCode, array $jsonData): void
    {
        $entityManager = $this->entityManager;
        $currency = $entityManager->getRepository(Currency::class)->findOneBy([
            'codeFrom' => $currencyCode,
            'codeTo' => $jsonData['code']
        ]);

        if (!$currency) {
            $currency = new Currency();
            $currency->setCodeFrom($currencyCode);
            $currency->setCodeTo($jsonData['code']);
        }

        $currency->setAlphaCode($jsonData['alphaCode']);
        $currency->setNumericCode($jsonData['numericCode']);
        $currency->setName($jsonData['name']);
        $currency->setRate($jsonData['rate']);
        $currency->setDate($jsonData['date']);
        $currency->setInverseRate($jsonData['inverseRate']);

        $entityManager->persist($currency);
        $entityManager->flush();
    }

    private function createCurrencyFromJson(string $currencyCode, array $jsonData): void
    {
        $entityManager = $this->entityManager;

        $currency = new Currency();
        $currency->setCodeFrom($currencyCode);
        $currency->setCodeTo($jsonData['code']);
        $currency->setAlphaCode($jsonData['alphaCode']);
        $currency->setNumericCode($jsonData['numericCode']);
        $currency->setName($jsonData['name']);
        $currency->setRate($jsonData['rate']);
        $currency->setDate($jsonData['date']);
        $currency->setInverseRate($jsonData['inverseRate']);

        $entityManager->persist($currency);
        $entityManager->flush();

    }

}
