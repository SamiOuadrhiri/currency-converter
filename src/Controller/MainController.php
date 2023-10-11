<?php

namespace App\Controller;

use App\Entity\Log;
use App\Entity\WhitelistedIP;
use App\Service\LoggerService;
use App\Repository\CurrencyCodeRepository;
use App\Repository\CurrencyRepository;
use App\Repository\UserRepository;
use App\Repository\WhitelistedIPRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    private HttpClientInterface $httpClient;
    private EntityManagerInterface $entityManager;
    public function __construct(HttpClientInterface $httpClient, EntityManagerInterface $entityManager)
    {
        $this->httpClient = $httpClient;
        $this->entityManager = $entityManager;
    }

    private function createLogEntry($description, $sourceFile, $entityManager)
    {
        $logEntry = new Log();

        $dateTime = new \DateTime();
        $dateString = $dateTime->format('Y-m-d H:i:s');

        $logEntry->setDescription($description);
        $logEntry->setSourceFile($sourceFile);
        $logEntry->setDate($dateString);

        $entityManager->persist($logEntry);
        $entityManager->flush();
    }

    #[Route('/', name: 'home')]
    public function index(CurrencyCodeRepository $currencyCodeRepository, CurrencyRepository $currencyRepository, Request $request): Response
    {
        $currencyCodes = $currencyCodeRepository->findAll();
        $currencies = [];

        if ($request->isMethod('POST')) {
            $selectedCurrency = $request->request->get('selected_currency');
            $amount = $request->request->get('amount');

            $currencies = $currencyRepository->findBy(['codeFrom' => $selectedCurrency]);

            return $this->render('main/index.html.twig', [
                'currencyCodes' => $currencyCodes,
                'currencies' => $currencies,
                'amount' => $amount,
            ]);
        }

        return $this->render('main/index.html.twig', [
            'currencyCodes' => $currencyCodes,
        ]);
    }

    #[Route('/users', name: 'users')]
    public function users(UserRepository $userRepository) {

        $users = $userRepository->findAll();

        return $this->render('main/users.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/ips', name: 'ips')]
    public function ips(WhitelistedIPRepository $whitelistedIPRepository) {

        $ips = $whitelistedIPRepository->findAll();

        return $this->render('main/ips.html.twig', [
            'ips' => $ips
        ]);
    }

    #[Route('/create-ip', name: 'create-ip')]
    public function create(Request $request, WhitelistedIPRepository $whitelistedIPRepository, LoggerService $logger) {

        $form = $this->createFormBuilder()
            ->add('IP_adres')
            ->add('IP_adres_Whitelisten', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted())
        {
            $data = $form->getData();
            $ip = new WhitelistedIP();
            $ip->setIp($data['IP_adres']);

            $entityManager = $this->entityManager;
            $entityManager->persist($ip);
            $entityManager->flush();

            $logger->createLogEntry('IP adres ' . $data['IP_adres'] . ' op whitelist geplaatst', __FILE__);

            $ips = $whitelistedIPRepository->findAll();

            return $this->render('main/ips.html.twig', [
                'ips' => $ips
            ]);
        }

        return $this->render('auth/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/delete-ip/{id}', name: 'delete-ip')]
    public function delete(WhitelistedIP $whitelistedIP, LoggerService $logger): RedirectResponse
    {
        $ip = $whitelistedIP->getIp();
        $em = $this->entityManager;
        $em->remove($whitelistedIP);
        $em->flush();

        $this->addFlash(type: 'success', message: 'IP is van whitelist gehaald');
        $logger->createLogEntry('IP adres ' . $ip . ' van whitelist gehaald', __FILE__);

        return $this->redirect($this->generateUrl( route: 'ips' ));
    }
}
