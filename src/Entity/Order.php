<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Subscription::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $subscription_id;

    /**
     * @ORM\ManyToOne(targetEntity=OrderStatus::class)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=OrderType::class)
     */
    private $type;

    /**
     * @ORM\Column(type="datetime")
     */
    private $order_date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubscriptionId(): ?Subscription
    {
        return $this->subscription_id;
    }

    public function setSubscriptionId(?Subscription $subscription_id): self
    {
        $this->subscription_id = $subscription_id;

        return $this;
    }

    public function getStatus(): ?Orderstatus
    {
        return $this->status;
    }

    public function setStatus(?Orderstatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getType(): ?OrderType
    {
        return $this->type;
    }

    public function setType(?OrderType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->order_date;
    }

    public function setOrderDate(\DateTimeInterface $order_date): self
    {
        $this->order_date = $order_date;

        return $this;
    }
}