<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AppConfig
 *
 * @ORM\Entity(repositoryClass="App\Repository\AppConfigRepository")
 */
class AppConfig
{
    /**
     * @var int
     *
     * @ORM\Column(name="record_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $recordId;

    /**
     * @var string
     *
     * @ORM\Column(name="field", type="text", length=65535, nullable=false)
     */
    private $field;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="text", length=65535, nullable=false)
     */
    private $value;

    public function getRecordId(): ?int
    {
        return $this->recordId;
    }

    public function getField(): ?string
    {
        return $this->field;
    }

    public function setField(string $field): self
    {
        $this->field = $field;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }


}
