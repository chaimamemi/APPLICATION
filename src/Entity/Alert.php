<?php

namespace App\Entity;

use App\Repository\AlertRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlertRepository::class)]
class Alert
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'alert', targetEntity: Bracelet::class)]
    private Collection $braceletId;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $timestamp = null;

    #[ORM\Column(length: 255)]
    private ?string $alertType = null;

    #[ORM\Column(length: 255)]
    private ?string $severity = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $handled = null;

    public function __construct()
    {
        $this->braceletId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Bracelet>
     */
    public function getBraceletId(): Collection
    {
        return $this->braceletId;
    }

    public function addBraceletId(Bracelet $braceletId): static
    {
        if (!$this->braceletId->contains($braceletId)) {
            $this->braceletId->add($braceletId);
            $braceletId->setAlert($this);
        }

        return $this;
    }

    public function removeBraceletId(Bracelet $braceletId): static
    {
        if ($this->braceletId->removeElement($braceletId)) {
            // set the owning side to null (unless already changed)
            if ($braceletId->getAlert() === $this) {
                $braceletId->setAlert(null);
            }
        }

        return $this;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): static
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getAlertType(): ?string
    {
        return $this->alertType;
    }

    public function setAlertType(string $alertType): static
    {
        $this->alertType = $alertType;

        return $this;
    }

    public function getSeverity(): ?string
    {
        return $this->severity;
    }

    public function setSeverity(string $severity): static
    {
        $this->severity = $severity;

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

    public function getHandled(): ?string
    {
        return $this->handled;
    }

    public function setHandled(string $handled): static
    {
        $this->handled = $handled;

        return $this;
    }
}
