<?php

namespace App\Entity;

use App\Repository\InterventionActionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InterventionActionRepository::class)]
class InterventionAction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'interventionActions')]
    private ?User $patient = null;

    #[ORM\ManyToOne(inversedBy: 'interventionActions')]
    private ?User $emergencyTeam = null;

    #[ORM\ManyToOne(inversedBy: 'interventionActions')]
    private ?User $alert = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateTime = null;

    #[ORM\Column(length: 255)]
    private ?string $action = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $otherAction = null;

    public function getPatient(): ?User
    {
        return $this->patient;
    }

    public function setPatient(?User $patient): static
    {
        $this->patient = $patient;

        return $this;
    }

    public function getEmergencyTeam(): ?User
    {
        return $this->emergencyTeam;
    }

    public function setEmergencyTeam(?User $emergencyTeam): static
    {
        $this->emergencyTeam = $emergencyTeam;

        return $this;
    }

    public function getAlert(): ?User
    {
        return $this->alert;
    }

    public function setAlert(?User $alert): static
    {
        $this->alert = $alert;

        return $this;
    }

    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTimeInterface $dateTime): static
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): static
    {
        $this->action = $action;

        return $this;
    }

    public function getOtherAction(): ?string
{
    return $this->otherAction;
}

public function setOtherAction(?string $otherAction): static
{
    $this->otherAction = $otherAction;
    return $this;
}
}
