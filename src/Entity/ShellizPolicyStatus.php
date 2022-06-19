<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShellizPolicyStatus
 *
 * @ORM\Table(name="shelliz_policy_status", indexes={@ORM\Index(name="policy_id", columns={"policy_id"}), @ORM\Index(name="record_id", columns={"record_id", "status", "renewal_frequency"}), @ORM\Index(name="renewal_frequency", columns={"renewal_frequency"})})
 * @ORM\Entity(repositoryClass="App\Repository\ShellizPolicyStatusRepository")
 */
class ShellizPolicyStatus
{
    /**
     * @var int
     *
     * @ORM\Column(name="record_id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $recordId;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $status;

    /**
     * @var int
     *
     * @ORM\Column(name="policy_id", type="bigint", nullable=false)
     */
    private $policyId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="onboard_date", type="datetime", nullable=false)
     */
    private $onboardDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="datetime", nullable=false)
     */
    private $endDate;

    /**
     * @var int
     *
     * @ORM\Column(name="renewal_frequency", type="bigint", nullable=false)
     */
    private $renewalFrequency;

    public function getRecordId(): ?string
    {
        return $this->recordId;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getPolicyId(): ?string
    {
        return $this->policyId;
    }

    public function setPolicyId(string $policyId): self
    {
        $this->policyId = $policyId;

        return $this;
    }

    public function getOnboardDate(): ?\DateTimeInterface
    {
        return $this->onboardDate;
    }

    public function setOnboardDate(\DateTimeInterface $onboardDate): self
    {
        $this->onboardDate = $onboardDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getRenewalFrequency(): ?string
    {
        return $this->renewalFrequency;
    }

    public function setRenewalFrequency(string $renewalFrequency): self
    {
        $this->renewalFrequency = $renewalFrequency;

        return $this;
    }


}
