<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Clients
 *
 * @ORM\Entity(repositoryClass="App\Repository\ClientsRepository")
 */
class Clients
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
     * @ORM\Column(name="user_name", type="text", length=65535, nullable=false)
     */
    private $userName;

    /**
     * @var string
     *
     * @ORM\Column(name="email_address", type="text", length=65535, nullable=false)
     */
    private $emailAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="text", length=65535, nullable=false)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="text", length=65535, nullable=false)
     */
    private $password;

    /**
     * @var int
     *
     * @ORM\Column(name="is_active", type="integer", nullable=false, options={"default"="1"})
     */
    private $isActive = 1;

    /**
     * @var string
     *
     * @ORM\Column(name="temp_otp", type="text", length=65535, nullable=false)
     */
    private $tempOtp;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="otp_time", type="datetime", nullable=false, options={"default"=NULL})
     */
    private $otpTime = NULL;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_of_joining", type="datetime", nullable=false, options={"default"=NULL})
     */
    private $dateOfJoining = NULL;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified_date", type="datetime", nullable=false, options={"default"=NULL})
     */
    private $modifiedDate = NULL;

    /**
     * @var string|null
     *
     * @ORM\Column(name="profile_picture", type="text", length=65535, nullable=true)
     */
    private $profilePicture;

    /**
     * @var int
     *
     * @ORM\Column(name="role", type="integer", nullable=false, options={"default"="1"})
     */
    private $role = 1;

    public function getRecordId(): ?string
    {
        return $this->recordId;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    public function setEmailAddress(string $emailAddress): self
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getIsActive(): ?int
    {
        return $this->isActive;
    }

    public function setIsActive(int $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getTempOtp(): ?string
    {
        return $this->tempOtp;
    }

    public function setTempOtp(string $tempOtp): self
    {
        $this->tempOtp = $tempOtp;

        return $this;
    }

    public function getOtpTime(): ?\DateTimeInterface
    {
        return $this->otpTime;
    }

    public function setOtpTime(\DateTimeInterface $otpTime): self
    {
        $this->otpTime = $otpTime;

        return $this;
    }

    public function getDateOfJoining(): ?\DateTimeInterface
    {
        return $this->dateOfJoining;
    }

    public function setDateOfJoining(\DateTimeInterface $dateOfJoining): self
    {
        $this->dateOfJoining = $dateOfJoining;

        return $this;
    }

    public function getModifiedDate(): ?\DateTimeInterface
    {
        return $this->modifiedDate;
    }

    public function setModifiedDate(\DateTimeInterface $modifiedDate): self
    {
        $this->modifiedDate = $modifiedDate;

        return $this;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(?string $profilePicture): self
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    public function getRole(): ?int
    {
        return $this->role;
    }

    public function setRole(int $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getTraiverVerificationStatus(): ?int
    {
        return $this->traiverVerificationStatus;
    }

    public function setTraiverVerificationStatus(int $traiverVerificationStatus): self
    {
        $this->traiverVerificationStatus = $traiverVerificationStatus;

        return $this;
    }

    public function getTrainerVerificationRemarks(): ?string
    {
        return $this->trainerVerificationRemarks;
    }

    public function setTrainerVerificationRemarks(string $trainerVerificationRemarks): self
    {
        $this->trainerVerificationRemarks = $trainerVerificationRemarks;

        return $this;
    }


}
