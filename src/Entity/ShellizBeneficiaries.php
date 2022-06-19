<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShellizBeneficiaries
 *
 * @ORM\Table(name="shelliz_beneficiaries", indexes={@ORM\Index(name="policy_id", columns={"policy_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\ShellizBeneficiariesRepository")
 */
class ShellizBeneficiaries
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
     * @ORM\Column(name="beneficiary_name", type="string", length=250, nullable=false)
     */
    private $beneficiaryName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="beneficiary_relationship", type="string", length=50, nullable=true)
     */
    private $beneficiaryRelationship;

    /**
     * @var int|null
     *
     * @ORM\Column(name="beneficiary_status", type="integer", nullable=true, options={"default"="1"})
     */
    private $beneficiaryStatus = 1;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="on_date", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $onDate = 'CURRENT_TIMESTAMP';

    /**
     * @var \ShellizPolicies
     *
     * @ORM\ManyToOne(targetEntity="ShellizPolicies")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="policy_id", referencedColumnName="record_id")
     * })
     */
    private $policy;

    public function getRecordId(): ?string
    {
        return $this->recordId;
    }

    public function getBeneficiaryName(): ?string
    {
        return $this->beneficiaryName;
    }

    public function setBeneficiaryName(string $beneficiaryName): self
    {
        $this->beneficiaryName = $beneficiaryName;

        return $this;
    }

    public function getBeneficiaryRelationship(): ?string
    {
        return $this->beneficiaryRelationship;
    }

    public function setBeneficiaryRelationship(?string $beneficiaryRelationship): self
    {
        $this->beneficiaryRelationship = $beneficiaryRelationship;

        return $this;
    }

    public function getBeneficiaryStatus(): ?int
    {
        return $this->beneficiaryStatus;
    }

    public function setBeneficiaryStatus(?int $beneficiaryStatus): self
    {
        $this->beneficiaryStatus = $beneficiaryStatus;

        return $this;
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


}
