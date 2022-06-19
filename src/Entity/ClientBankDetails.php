<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClientBankDetails
 *
 * @ORM\Table(name="client_bank_details", indexes={@ORM\Index(name="client_id", columns={"client_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\ClientBankDetailsRepository")
 */
class ClientBankDetails
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
     * @ORM\Column(name="client_id", type="bigint", nullable=false)
     */
    private $clientId;

    /**
     * @var string
     *
     * @ORM\Column(name="bank_name", type="string", length=100, nullable=false)
     */
    private $bankName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="bank_branch", type="string", length=100, nullable=true)
     */
    private $bankBranch;

    /**
     * @var string
     *
     * @ORM\Column(name="account_number", type="text", length=65535, nullable=false)
     */
    private $accountNumber;

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

    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    public function setClientId(string $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getBankName(): ?string
    {
        return $this->bankName;
    }

    public function setBankName(string $bankName): self
    {
        $this->bankName = $bankName;

        return $this;
    }

    public function getBankBranch(): ?string
    {
        return $this->bankBranch;
    }

    public function setBankBranch(?string $bankBranch): self
    {
        $this->bankBranch = $bankBranch;

        return $this;
    }

    public function getAccountNumber(): ?string
    {
        return $this->accountNumber;
    }

    public function setAccountNumber(string $accountNumber): self
    {
        $this->accountNumber = $accountNumber;

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
