<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShellizProducts
 *
 * @ORM\Table(name="shelliz_products", indexes={@ORM\Index(name="shelliz_product_type", columns={"shelliz_product_type"})})
 * @ORM\Entity(repositoryClass="App\Repository\ShellizProductsRepository")
 */
class ShellizProducts
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
     * @ORM\Column(name="product_name", type="string", length=200, nullable=false)
     */
    private $productName;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer", nullable=false, options={"default"="1"})
     */
    private $status = 1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="icon_path", type="text", length=65535, nullable=true)
     */
    private $iconPath;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_on", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdOn = 'CURRENT_TIMESTAMP';

    /**
     * @var \ShellizProductTypes
     *
     * @ORM\ManyToOne(targetEntity="ShellizProductTypes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="shelliz_product_type", referencedColumnName="record_id")
     * })
     */
    private $shellizProductType;

    public function getRecordId(): ?string
    {
        return $this->recordId;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): self
    {
        $this->productName = $productName;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getIconPath(): ?string
    {
        return $this->iconPath;
    }

    public function setIconPath(?string $iconPath): self
    {
        $this->iconPath = $iconPath;

        return $this;
    }

    public function getCreatedOn(): ?\DateTimeInterface
    {
        return $this->createdOn;
    }

    public function setCreatedOn(\DateTimeInterface $createdOn): self
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    public function getShellizProductType(): ?ShellizProductTypes
    {
        return $this->shellizProductType;
    }

    public function setShellizProductType(?ShellizProductTypes $shellizProductType): self
    {
        $this->shellizProductType = $shellizProductType;

        return $this;
    }


}
