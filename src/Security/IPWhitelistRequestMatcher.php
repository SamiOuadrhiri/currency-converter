<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use App\Repository\WhitelistedIPRepository;

class IPWhitelistRequestMatcher implements RequestMatcherInterface
{
    private $whitelistRepository;

    public function __construct(WhitelistedIPRepository $whitelistRepository)
    {
        $this->whitelistRepository = $whitelistRepository;
    }

    public function matches(Request $request): bool
    {
        $ip = $request->getClientIp();
        $isWhitelisted = $this->whitelistRepository->isIPWhitelisted($ip);

        return $isWhitelisted;
    }
}