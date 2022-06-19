<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShellizProductTypes
 *
 * @ORM\Table(name="shelliz_product_types")
 * @ORM\Entity(repositoryClass="App\Repository\ShellizProductTypesRepository")
 */
class ShellizProductTypes
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
     * @ORM\Column(name="product_type_name", type="string", length=50, nullable=false)
     */
    private $productTypeName;

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

    public function getProductTypeName(): ?string
    {
        return $this->productTypeName;
    }

    public function setProductTypeName(string $productTypeName): self
    {
        $this->productTypeName = $productTypeName;

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
