<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShellizTicketTypes
 *
 * @ORM\Table(name="shelliz_ticket_types")
 * @ORM\Entity(repositoryClass="App\Repository\ShellizTicketTypesRepository")
 */
class ShellizTicketTypes
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
     * @ORM\Column(name="ticket_type_description", type="text", length=65535, nullable=false)
     */
    private $ticketTypeDescription;

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

    public function getTicketTypeDescription(): ?string
    {
        return $this->ticketTypeDescription;
    }

    public function setTicketTypeDescription(string $ticketTypeDescription): self
    {
        $this->ticketTypeDescription = $ticketTypeDescription;

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
