<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClientContacts
 *
 * @ORM\Table(name="client_contacts", indexes={@ORM\Index(name="assoc_client_id", columns={"assoc_client_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\ClientContactsRepository")
 */
class ClientContacts
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
     * @ORM\Column(name="mobile_1", type="text", length=65535, nullable=false)
     */
    private $mobile1;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile_two", type="text", length=65535, nullable=false)
     */
    private $mobileTwo;

    /**
     * @var string
     *
     * @ORM\Column(name="primary_email", type="text", length=65535, nullable=false)
     */
    private $primaryEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="other_email", type="text", length=65535, nullable=false)
     */
    private $otherEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="preferred_mode_of_communication", type="text", length=65535, nullable=false)
     */
    private $preferredModeOfCommunication;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="best_time_to_call", type="time", nullable=true)
     */
    private $bestTimeToCall;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_time", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdTime = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified_time", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $modifiedTime = 'CURRENT_TIMESTAMP';

    /**
     * @var \Clients
     *
     * @ORM\ManyToOne(targetEntity="Clients")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="assoc_client_id", referencedColumnName="record_id")
     * })
     */
    private $assocClient;

    public function getRecordId(): ?string
    {
        return $this->recordId;
    }

    public function getMobile1(): ?string
    {
        return $this->mobile1;
    }

    public function setMobile1(string $mobile1): self
    {
        $this->mobile1 = $mobile1;

        return $this;
    }

    public function getMobileTwo(): ?string
    {
        return $this->mobileTwo;
    }

    public function setMobileTwo(string $mobileTwo): self
    {
        $this->mobileTwo = $mobileTwo;

        return $this;
    }

    public function getPrimaryEmail(): ?string
    {
        return $this->primaryEmail;
    }

    public function setPrimaryEmail(string $primaryEmail): self
    {
        $this->primaryEmail = $primaryEmail;

        return $this;
    }

    public function getOtherEmail(): ?string
    {
        return $this->otherEmail;
    }

    public function setOtherEmail(string $otherEmail): self
    {
        $this->otherEmail = $otherEmail;

        return $this;
    }

    public function getPreferredModeOfCommunication(): ?string
    {
        return $this->preferredModeOfCommunication;
    }

    public function setPreferredModeOfCommunication(string $preferredModeOfCommunication): self
    {
        $this->preferredModeOfCommunication = $preferredModeOfCommunication;

        return $this;
    }

    public function getBestTimeToCall(): ?\DateTimeInterface
    {
        return $this->bestTimeToCall;
    }

    public function setBestTimeToCall(?\DateTimeInterface $bestTimeToCall): self
    {
        $this->bestTimeToCall = $bestTimeToCall;

        return $this;
    }

    public function getCreatedTime(): ?\DateTimeInterface
    {
        return $this->createdTime;
    }

    public function setCreatedTime(\DateTimeInterface $createdTime): self
    {
        $this->createdTime = $createdTime;

        return $this;
    }

    public function getModifiedTime(): ?\DateTimeInterface
    {
        return $this->modifiedTime;
    }

    public function setModifiedTime(\DateTimeInterface $modifiedTime): self
    {
        $this->modifiedTime = $modifiedTime;

        return $this;
    }

    public function getAssocClient(): ?Clients
    {
        return $this->assocClient;
    }

    public function setAssocClient(?Clients $assocClient): self
    {
        $this->assocClient = $assocClient;

        return $this;
    }


}
