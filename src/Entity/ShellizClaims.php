<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShellizClaims
 *
 * @ORM\Table(name="shelliz_claims", indexes={@ORM\Index(name="claim_status", columns={"claim_status"}), @ORM\Index(name="on_date", columns={"on_date"}), @ORM\Index(name="policy_id", columns={"policy_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\ShellizClaimsRepository")
 */
class ShellizClaims
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
     * @var \DateTime
     *
     * @ORM\Column(name="on_date", type="datetime", nullable=false)
     */
    private $onDate;

    /**
     * @var \ShellizPolicies
     *
     * @ORM\ManyToOne(targetEntity="ShellizPolicies")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="policy_id", referencedColumnName="record_id")
     * })
     */
    private $policy;

    /**
     * @var \ShellizClaimStatus
     *
     * @ORM\ManyToOne(targetEntity="ShellizClaimStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="claim_status", referencedColumnName="record_id")
     * })
     */
    private $claimStatus;

    public function getRecordId(): ?string
    {
        return $this->recordId;
    }

    public function getOnDate(): ?\DateTimeInterface
    {
        return $this->onDate;
    }

    public function setOnDate(\DateTimeInterface $onDate): self
    {
        $this->onDate = $onDate;

        return $this;
    }

    public function getPolicy(): ?ShellizPolicies
    {
        return $this->policy;
    }

    public function setPolicy(?ShellizPolicies $policy): self
    {
        $this->policy = $policy;

        return $this;
    }

    public function getClaimStatus(): ?ShellizClaimStatus
    {
        return $this->claimStatus;
    }

    public function setClaimStatus(?ShellizClaimStatus $claimStatus): self
    {
        $this->claimStatus = $claimStatus;

        return $this;
    }


}
