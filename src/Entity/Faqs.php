<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Faqs
 *
 * @ORM\Table(name="faqs")
 * @ORM\Entity(repositoryClass="App\Repository\FaqsRepository")
 */
class Faqs
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
     * @ORM\Column(name="question", type="string", length=250, nullable=false)
     */
    private $question;

    /**
     * @var string
     *
     * @ORM\Column(name="answer", type="text", length=65535, nullable=false)
     */
    private $answer;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer", nullable=false, options={"default"="1"})
     */
    private $status = 1;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="on_date", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $onDate = 'CURRENT_TIMESTAMP';

    public function getRecordId(): ?string
    {
        return $this->recordId;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): self
    {
        $this->answer = $answer;

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

    public function getOnDate(): ?\DateTimeInterface
    {
        return $this->onDate;
    }

    public function setOnDate(\DateTimeInterface $onDate): self
    {
        $this->onDate = $onDate;

        return $this;
    }


}
