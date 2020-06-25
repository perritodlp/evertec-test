<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PaymentAttempt
 *
 * @ORM\Table(name="payment_attempt")
 * @ORM\Entity
 */
class PaymentAttempt
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
     * @ORM\Column(name="customer_id", type="integer", nullable=false)
     */
    private $customerId;

    /**
     * @var int
     *
     * @ORM\Column(name="order_id", type="integer", nullable=false)
     */
    private $orderId;

    /**
     * @var int
     *
     * @ORM\Column(name="payment_method_id", type="integer", nullable=false)
     */
    private $paymentMethodId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="source_url", type="string", length=256, nullable=true)
     */
    private $sourceUrl;

    /**
     * @var string|null
     *
     * @ORM\Column(name="success_return_url", type="string", length=256, nullable=true)
     */
    private $successReturnUrl;

    /**
     * @var string|null
     *
     * @ORM\Column(name="error_return_url", type="string", length=256, nullable=true)
     */
    private $errorReturnUrl;

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

    public function getCustomerId(): ?int
    {
        return $this->customerId;
    }

    public function setCustomerId(int $customerId): self
    {
        $this->customerId = $customerId;

        return $this;
    }

    public function getOrderId(): ?int
    {
        return $this->orderId;
    }

    public function setOrderId(int $orderId): self
    {
        $this->orderId = $orderId;

        return $this;
    }

    public function getPaymentMethodId(): ?int
    {
        return $this->paymentMethodId;
    }

    public function setPaymentMethodId(int $paymentMethodId): self
    {
        $this->paymentMethodId = $paymentMethodId;

        return $this;
    }

    public function getSourceUrl(): ?string
    {
        return $this->sourceUrl;
    }

    public function setSourceUrl(?string $sourceUrl): self
    {
        $this->sourceUrl = $sourceUrl;

        return $this;
    }

    public function getSuccessReturnUrl(): ?string
    {
        return $this->successReturnUrl;
    }

    public function setSuccessReturnUrl(?string $successReturnUrl): self
    {
        $this->successReturnUrl = $successReturnUrl;

        return $this;
    }

    public function getErrorReturnUrl(): ?string
    {
        return $this->errorReturnUrl;
    }

    public function setErrorReturnUrl(?string $errorReturnUrl): self
    {
        $this->errorReturnUrl = $errorReturnUrl;

        return $this;
    }

    public function getExternalReference(): ?string
    {
        return $this->externalReference;
    }

    public function setExternalReference(?string $externalReference): self
    {
        $this->externalReference = $externalReference;

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
