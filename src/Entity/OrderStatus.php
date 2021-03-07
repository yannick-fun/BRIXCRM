<?php

namespace App\Entity;

use App\Repository\OrderStatusRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderStatusRepository::class)
 */
class OrderStatus
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status_option;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatusOption(): ?string
    {
        return $this->status_option;
    }

    public function setStatusOption(string $status_option): self
    {
        $this->status_option = $status_option;

        return $this;
    }

}