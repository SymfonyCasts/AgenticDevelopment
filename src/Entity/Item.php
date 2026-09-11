<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    /**
     * A complete Tailwind class literal (e.g. "bg-[#2C4A52]") used as the card background.
     */
    #[ORM\Column(length: 255)]
    private ?string $colorClass = null;

    #[ORM\Column(length: 255)]
    private ?string $foundAt = null;

    #[ORM\Column(type: 'text')]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getColorClass(): ?string
    {
        return $this->colorClass;
    }

    public function setColorClass(string $colorClass): static
    {
        $this->colorClass = $colorClass;

        return $this;
    }

    public function getFoundAt(): ?string
    {
        return $this->foundAt;
    }

    public function setFoundAt(string $foundAt): static
    {
        $this->foundAt = $foundAt;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
