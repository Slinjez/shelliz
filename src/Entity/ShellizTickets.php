<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShellizTickets
 *
 * @ORM\Table(name="shelliz_tickets", indexes={@ORM\Index(name="client_id", columns={"client_id"}), @ORM\Index(name="ticket_type", columns={"ticket_type"})})
 * @ORM\Entity(repositoryClass="App\Repository\ShellizTicketsRepository")
 */
class ShellizTickets
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
     * @ORM\Column(name="ticket_subject", type="text", length=65535, nullable=false)
     */
    private $ticketSubject;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", length=65535, nullable=false)
     */
    private $message;

    /**
     * @var int
     *
     * @ORM\Column(name="stage", type="integer", nullable=false)
     */
    private $stage = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="on_date", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $onDate = 'CURRENT_TIMESTAMP';

    /**
     * @var \ShellizTicketTypes
     *
     * @ORM\ManyToOne(targetEntity="ShellizTicketTypes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ticket_type", referencedColumnName="record_id")
     * })
     */
    private $ticketType;

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

    public function getTicketSubject(): ?string
    {
        return $this->ticketSubject;
    }

    public function setTicketSubject(string $ticketSubject): self
    {
        $this->ticketSubject = $ticketSubject;

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

    public function getStage(): ?int
    {
        return $this->stage;
    }

    public function setStage(int $stage): self
    {
        $this->stage = $stage;

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

    public function getTicketType(): ?ShellizTicketTypes
    {
        return $this->ticketType;
    }

    public function setTicketType(?ShellizTicketTypes $ticketType): self
    {
        $this->ticketType = $ticketType;

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
