<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Continents
 *
 * @ORM\Table(name="continents")
 * @ORM\Entity(repositoryClass="App\Repository\ContinentsRepository")
 */
class Continents
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
     * @var string|null
     *
     * @ORM\Column(name="continent_code", type="string", length=2, nullable=true)
     */
    private $continentCode;

    /**
     * @var string|null
     *
     * @ORM\Column(name="continent_name", type="string", length=30, nullable=true)
     */
    private $continentName;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getContinentName(): ?string
    {
        return $this->continentName;
    }

    public function setContinentName(?string $continentName): self
    {
        $this->continentName = $continentName;

        return $this;
    }


}
