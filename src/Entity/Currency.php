<?php

namespace App\Entity;

use App\Repository\CurrencyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
class Currency
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $codeFrom = null;

    #[ORM\Column(length: 255)]
    private ?string $codeTo = null;

    #[ORM\Column(length: 255)]
    private ?string $alphaCode = null;

    #[ORM\Column(length: 255)]
    private ?string $numericCode = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $rate = null;

    #[ORM\Column(length: 255)]
    private ?string $date = null;

    #[ORM\Column]
    private ?float $inverseRate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeFrom(): ?string
    {
        return $this->codeFrom;
    }

    public function setCodeFrom(string $code): static
    {
        $this->codeFrom = $code;

        return $this;
    }

    public function getCodeTo(): ?string
    {
        return $this->codeTo;
    }

    public function setCodeTo(string $code): static
    {
        $this->codeTo = $code;

        return $this;
    }

    public function getAlphaCode(): ?string
    {
        return $this->alphaCode;
    }

    public function setAlphaCode(string $alphaCode): static
    {
        $this->alphaCode = $alphaCode;

        return $this;
    }

    public function getNumericCode(): ?string
    {
        return $this->numericCode;
    }

    public function setNumericCode(string $numericCode): static
    {
        $this->numericCode = $numericCode;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(float $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getInverseRate(): ?float
    {
        return $this->inverseRate;
    }

    public function setInverseRate(float $inverseRate): static
    {
        $this->inverseRate = $inverseRate;

        return $this;
    }
}
