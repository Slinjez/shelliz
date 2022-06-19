<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShellizPolicyBlueprint
 *
 * @ORM\Table(name="shelliz_policy_blueprint", indexes={@ORM\Index(name="policy_type", columns={"policy_type"}), @ORM\Index(name="renewal_frequency", columns={"renewal_frequency"})})
 * @ORM\Entity(repositoryClass="App\Repository\ShellizPolicyBlueprintRepository")
 */
class ShellizPolicyBlueprint
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
     * @ORM\Column(name="policy_type", type="string", length=200, nullable=false)
     */
    private $policyType;

    /**
     * @var int
     *
     * @ORM\Column(name="sum_assured", type="bigint", nullable=false)
     */
    private $sumAssured;

    /**
     * @var int
     *
     * @ORM\Column(name="sum_insured", type="bigint", nullable=false)
     */
    private $sumInsured;

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

    public function getPolicyType(): ?string
    {
        return $this->policyType;
    }

    public function setPolicyType(string $policyType): self
    {
        $this->policyType = $policyType;

        return $this;
    }

    public function getSumAssured(): ?string
    {
        return $this->sumAssured;
    }

    public function setSumAssured(string $sumAssured): self
    {
        $this->sumAssured = $sumAssured;

        return $this;
    }

    public function getSumInsured(): ?string
    {
        return $this->sumInsured;
    }

    public function setSumInsured(string $sumInsured): self
    {
        $this->sumInsured = $sumInsured;

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
