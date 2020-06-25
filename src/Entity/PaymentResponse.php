<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PaymentResponse
 *
 * @ORM\Table(name="payment_response")
 * @ORM\Entity
 */
class PaymentResponse
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="payment_attempt_id", type="integer", nullable=false)
     */
    private $paymentAttemptId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="operation_code", type="string", length=4, nullable=true)
     */
    private $operationCode;

    /**
     * @var string|null
     *
     * @ORM\Column(name="status_code", type="string", length=40, nullable=true)
     */
    private $statusCode;

    /**
     * @var string|null
     *
     * @ORM\Column(name="status_info", type="string", length=120, nullable=true)
     */
    private $statusInfo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="additional_info", type="string", length=256, nullable=true)
     */
    private $additionalInfo;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaymentAttemptId(): ?int
    {
        return $this->paymentAttemptId;
    }

    public function setPaymentAttemptId(int $paymentAttemptId): self
    {
        $this->paymentAttemptId = $paymentAttemptId;

        return $this;
    }

    public function getOperationCode(): ?string
    {
        return $this->operationCode;
    }

    public function setOperationCode(?string $operationCode): self
    {
        $this->operationCode = $operationCode;

        return $this;
    }

    public function getStatusCode(): ?string
    {
        return $this->statusCode;
    }

    public function setStatusCode(?string $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function getStatusInfo(): ?string
    {
        return $this->statusInfo;
    }

    public function setStatusInfo(?string $statusInfo): self
    {
        $this->statusInfo = $statusInfo;

        return $this;
    }

    public function getAdditionalInfo(): ?string
    {
        return $this->additionalInfo;
    }

    public function setAdditionalInfo(?string $additionalInfo): self
    {
        $this->additionalInfo = $additionalInfo;

        return $this;
    }

    /**
     * Gets triggered only on insert
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->createdAt = new \DateTime("now");
    }

    /**
     * Gets triggered every time on update
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updatedAt = new \DateTime("now");
    }

}
