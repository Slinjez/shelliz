<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShellizPolicies
 *
 * @ORM\Table(name="shelliz_policies", indexes={@ORM\Index(name="client_id", columns={"client_id"}), @ORM\Index(name="client_id_2", columns={"client_id"}), @ORM\Index(name="policy_type", columns={"product_id"}), @ORM\Index(name="policy_type_2", columns={"product_id"}), @ORM\Index(name="renewal_frequency", columns={"renewal_frequency"})})
 * @ORM\Entity(repositoryClass="App\Repository\ShellizPoliciesRepository")
 */
class ShellizPolicies
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
     * @ORM\Column(name="policy_start_date", type="datetime", nullable=false)
     */
    private $policyStartDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="policy_end_date", type="datetime", nullable=false)
     */
    private $policyEndDate;

    /**
     * @var string
     *
     * @ORM\Column(name="extra_info", type="text", length=65535, nullable=false)
     */
    private $extraInfo;

    /**
     * @var int
     *
     * @ORM\Column(name="parent_lead", type="bigint", nullable=false)
     */
    private $parentLead = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="book_date", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $bookDate = 'CURRENT_TIMESTAMP';

    /**
     * @var \ShellizProducts
     *
     * @ORM\ManyToOne(targetEntity="ShellizProducts")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="record_id")
     * })
     */
    private $product;

    /**
     * @var \ShellizRenewalFrequency
     *
     * @ORM\ManyToOne(targetEntity="ShellizRenewalFrequency")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="renewal_frequency", referencedColumnName="record_id")
     * })
     */
    private $renewalFrequency;

    /**
     * @var \Clients
     *
     * @ORM\ManyToOne(targetEntity="Clients")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client_id", referencedColumnName="record_id")
     * })
     */
    private $client;

    public function getRecordId(): ?string
    {
        return $this->recordId;
    }

    public function getPolicyStartDate(): ?\DateTimeInterface
    {
        return $this->policyStartDate;
    }

    public function setPolicyStartDate(\DateTimeInterface $policyStartDate): self
    {
        $this->policyStartDate = $policyStartDate;

        return $this;
    }

    public function getPolicyEndDate(): ?\DateTimeInterface
    {
        return $this->policyEndDate;
    }

    public function setPolicyEndDate(\DateTimeInterface $policyEndDate): self
    {
        $this->policyEndDate = $policyEndDate;

        return $this;
    }

    public function getExtraInfo(): ?string
    {
        return $this->extraInfo;
    }

    public function setExtraInfo(string $extraInfo): self
    {
        $this->extraInfo = $extraInfo;

        return $this;
    }

    public function getParentLead(): ?string
    {
        return $this->parentLead;
    }

    public function setParentLead(string $parentLead): self
    {
        $this->parentLead = $parentLead;

        return $this;
    }

    public function getBookDate(): ?\DateTimeInterface
    {
        return $this->bookDate;
    }

    public function setBookDate(\DateTimeInterface $bookDate): self
    {
        $this->bookDate = $bookDate;

        return $this;
    }

    public function getProduct(): ?ShellizProducts
    {
        return $this->product;
    }

    public function setProduct(?ShellizProducts $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getRenewalFrequency(): ?ShellizRenewalFrequency
    {
        return $this->renewalFrequency;
    }

    public function setRenewalFrequency(?ShellizRenewalFrequency $renewalFrequency): self
    {
        $this->renewalFrequency = $renewalFrequency;

        return $this;
    }

    public function getClient(): ?Clients
    {
        return $this->client;
    }

    public function setClient(?Clients $client): self
    {
        $this->client = $client;

        return $this;
    }


}
