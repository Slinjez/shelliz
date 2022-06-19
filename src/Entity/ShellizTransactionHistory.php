<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShellizTransactionHistory
 *
 * @ORM\Table(name="shelliz_transaction_history", indexes={@ORM\Index(name="policy_id", columns={"policy_id"}), @ORM\Index(name="transaction_status", columns={"transaction_status"}), @ORM\Index(name="transaction_type", columns={"transaction_type"})})
 * @ORM\Entity(repositoryClass="App\Repository\liShelzShellizTransactionHistoryRepository")
 */
class ShellizTransactionHistory
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
     * @var int
     *
     * @ORM\Column(name="policy_id", type="bigint", nullable=false)
     */
    private $policyId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="transaction_type", type="bigint", nullable=false)
     */
    private $transactionType;

    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="bigint", nullable=false)
     */
    private $amount;

    /**
     * @var int
     *
     * @ORM\Column(name="transaction_status", type="bigint", nullable=false)
     */
    private $transactionStatus;

    public function getRecordId(): ?string
    {
        return $this->recordId;
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTransactionType(): ?string
    {
        return $this->transactionType;
    }

    public function setTransactionType(string $transactionType): self
    {
        $this->transactionType = $transactionType;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getTransactionStatus(): ?string
    {
        return $this->transactionStatus;
    }

    public function setTransactionStatus(string $transactionStatus): self
    {
        $this->transactionStatus = $transactionStatus;

        return $this;
    }


}
