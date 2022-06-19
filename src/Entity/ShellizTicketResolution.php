<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShellizTicketResolution
 *
 * @ORM\Table(name="shelliz_ticket_resolution", indexes={@ORM\Index(name="admin_id", columns={"admin_id"}), @ORM\Index(name="client_id", columns={"client_id"}), @ORM\Index(name="ticket_id", columns={"ticket_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\ShellizTicketResolutionRepository")
 */
class ShellizTicketResolution
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
     * @ORM\Column(name="admin_id", type="bigint", nullable=false)
     */
    private $adminId = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="client_id", type="bigint", nullable=false)
     */
    private $clientId;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", length=65535, nullable=false)
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="at_time", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $atTime = 'CURRENT_TIMESTAMP';

    /**
     * @var \ShellizTickets
     *
     * @ORM\ManyToOne(targetEntity="ShellizTickets")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ticket_id", referencedColumnName="record_id")
     * })
     */
    private $ticket;

    public function getRecordId(): ?string
    {
        return $this->recordId;
    }

    public function getAdminId(): ?string
    {
        return $this->adminId;
    }

    public function setAdminId(string $adminId): self
    {
        $this->adminId = $adminId;

        return $this;
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

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getAtTime(): ?\DateTimeInterface
    {
        return $this->atTime;
    }

    public function setAtTime(\DateTimeInterface $atTime): self
    {
        $this->atTime = $atTime;

        return $this;
    }

    public function getTicket(): ?ShellizTickets
    {
        return $this->ticket;
    }

    public function setTicket(?ShellizTickets $ticket): self
    {
        $this->ticket = $ticket;

        return $this;
    }


}
