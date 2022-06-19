<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShellizRenewalFrequency
 *
 * @ORM\Table(name="shelliz_renewal_frequency")
 * @ORM\Entity(repositoryClass="App\Repository\ShellizRenewalFrequencyRepository")
 */
class ShellizRenewalFrequency
{
    /**
     * @var int
     *
     * @ORM\Column(name="record_id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $recordId;

    /**
     * @var string
     *
     * @ORM\Column(name="frequency_name", type="text", length=65535, nullable=false)
     */
    private $frequencyName;

    /**
     * @var string
     *
     * @ORM\Column(name="frequency_description", type="text", length=65535, nullable=false)
     */
    private $frequencyDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="formula", type="text", length=65535, nullable=false)
     */
    private $formula;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer", nullable=false, options={"default"="1"})
     */
    private $status = 1;

    public function getRecordId(): ?string
    {
        return $this->recordId;
    }

    public function getFrequencyName(): ?string
    {
        return $this->frequencyName;
    }

    public function setFrequencyName(string $frequencyName): self
    {
        $this->frequencyName = $frequencyName;

        return $this;
    }

    public function getFrequencyDescription(): ?string
    {
        return $this->frequencyDescription;
    }

    public function setFrequencyDescription(string $frequencyDescription): self
    {
        $this->frequencyDescription = $frequencyDescription;

        return $this;
    }

    public function getFormula(): ?string
    {
        return $this->formula;
    }

    public function setFormula(string $formula): self
    {
        $this->formula = $formula;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }


}
