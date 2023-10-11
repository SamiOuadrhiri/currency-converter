<?php

namespace App\Entity;

use App\Repository\WhitelistedIPRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WhitelistedIPRepository::class)]
class WhitelistedIP
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $IP = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIP(): ?string
    {
        return $this->IP;
    }

    public function setIP(string $IP): static
    {
        $this->IP = $IP;

        return $this;
    }
}
