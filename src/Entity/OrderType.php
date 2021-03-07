<?php

namespace App\Entity;

use App\Repository\OrderTypeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderTypeRepository::class)
 */
class OrderType
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
    private $type_option;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeOption(): ?string
    {
        return $this->type_option;
    }

    public function setTypeOption(string $type_option): self
    {
        $this->type_option = $type_option;

        return $this;
    }
}