<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Countries
 *
 * @ORM\Table(name="countries")
 * @ORM\Entity(repositoryClass="App\Repository\CountriesRepository")
 */
class Countries
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="phone", type="integer", nullable=false)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=2, nullable=false, options={"fixed"=true})
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=80, nullable=false)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="symbol", type="string", length=10, nullable=true)
     */
    private $symbol;

    /**
     * @var string|null
     *
     * @ORM\Column(name="capital", type="string", length=80, nullable=true)
     */
    private $capital;

    /**
     * @var string|null
     *
     * @ORM\Column(name="currency", type="string", length=3, nullable=true)
     */
    private $currency;

    /**
     * @var string|null
     *
     * @ORM\Column(name="continent", type="string", length=30, nullable=true)
     */
    private $continent;

    /**
     * @var string|null
     *
     * @ORM\Column(name="continent_code", type="string", length=2, nullable=true)
     */
    private $continentCode;

    /**
     * @var string|null
     *
     * @ORM\Column(name="alpha_3", type="string", length=3, nullable=true, options={"fixed"=true})
     */
    private $alpha3;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(?string $symbol): self
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getCapital(): ?string
    {
        return $this->capital;
    }

    public function setCapital(?string $capital): self
    {
        $this->capital = $capital;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getContinent(): ?string
    {
        return $this->continent;
    }

    public function setContinent(?string $continent): self
    {
        $this->continent = $continent;

        return $this;
    }

    public function getContinentCode(): ?string
    {
        return $this->continentCode;
    }

    public function setContinentCode(?string $continentCode): self
    {
        $this->continentCode = $continentCode;

        return $this;
    }

    public function getAlpha3(): ?string
    {
        return $this->alpha3;
    }

    public function setAlpha3(?string $alpha3): self
    {
        $this->alpha3 = $alpha3;

        return $this;
    }


}
