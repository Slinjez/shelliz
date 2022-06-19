<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShellizClaimStatus
 *
 * @ORM\Table(name="shelliz_claim_status")
 * @ORM\Entity(repositoryClass="App\Repository\ShellizClaimStatusRepository")
 */
class ShellizClaimStatus
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
     * @ORM\Column(name="status_description", type="text", length=65535, nullable=false)
     */
    private $statusDescription;

    public function getRecordId(): ?string
    {
        return $this->recordId;
    }

    public function getStatusDescription(): ?string
    {
        return $this->statusDescription;
    }

    public function setStatusDescription(string $statusDescription): self
    {
        $this->statusDescription = $statusDescription;

        return $this;
    }


}
