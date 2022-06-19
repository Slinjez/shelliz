<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClientProfileData
 *
 * @ORM\Table(name="client_profile_data", uniqueConstraints={@ORM\UniqueConstraint(name="client_id", columns={"client_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\ClientProfileDataRepository")
 */
class ClientProfileData
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
     * @ORM\Column(name="national_id", type="string", length=50, nullable=false)
     */
    private $nationalId;

    /**
     * @var string
     *
     * @ORM\Column(name="pin", type="string", length=50, nullable=false)
     */
    private $pin;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=200, nullable=false)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=200, nullable=false)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="postal_code", type="string", length=200, nullable=false)
     */
    private $postalCode;

    /**
     * @var int
     *
     * @ORM\Column(name="country", type="bigint", nullable=false)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=200, nullable=false)
     */
    private $website;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook", type="string", length=200, nullable=false)
     */
    private $facebook;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter", type="string", length=200, nullable=false)
     */
    private $twitter;

    /**
     * @var string
     *
     * @ORM\Column(name="instagram", type="string", length=200, nullable=false)
     */
    private $instagram;

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

    public function getNationalId(): ?string
    {
        return $this->nationalId;
    }

    public function setNationalId(string $nationalId): self
    {
        $this->nationalId = $nationalId;

        return $this;
    }

    public function getPin(): ?string
    {
        return $this->pin;
    }

    public function setPin(string $pin): self
    {
        $this->pin = $pin;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(string $twitter): self
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(string $instagram): self
    {
        $this->instagram = $instagram;

        return $this;
    }


}
