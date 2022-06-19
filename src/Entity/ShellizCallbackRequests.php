<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShellizCallbackRequests
 *
 * @ORM\Table(name="shelliz_callback_requests", indexes={@ORM\Index(name="client_id", columns={"client_id"}), @ORM\Index(name="product_id", columns={"product_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\ShellizCallbackRequestsRepository")
 */
class ShellizCallbackRequests
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
     * @ORM\Column(name="call_back_time", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $callBackTime = 'CURRENT_TIMESTAMP';

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer", nullable=false, options={"default"="1"})
     */
    private $status = 1;

    /**
     * @var \Clients
     *
     * @ORM\ManyToOne(targetEntity="Clients")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client_id", referencedColumnName="record_id")
     * })
     */
    private $client;

    /**
     * @var \ShellizProducts
     *
     * @ORM\ManyToOne(targetEntity="ShellizProducts")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="record_id")
     * })
     */
    private $product;

    public function getRecordId(): ?string
    {
        return $this->recordId;
    }

    public function getCallBackTime(): ?\DateTimeInterface
    {
        return $this->callBackTime;
    }

    public function setCallBackTime(\DateTimeInterface $callBackTime): self
    {
        $this->callBackTime = $callBackTime;

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

    public function getClient(): ?Clients
    {
        return $this->client;
    }

    public function setClient(?Clients $client): self
    {
        $this->client = $client;

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


}
