<?php

namespace App\Entity;

use App\Repository\MedicationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MedicationRepository::class)]
class Medication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $nameMedication = null;

    #[ORM\Column(length: 255)]
    private ?string $medicalNote = null;

    #[ORM\Column(length: 255)]
    private ?string $dosage = null;

    #[ORM\ManyToOne(inversedBy: 'medication')]
    private ?BiologicalData $biologicalData = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNameMedication(): ?string
    {
        return $this->nameMedication;
    }

    public function setNameMedication(string $nameMedication): static
    {
        $this->nameMedication = $nameMedication;

        return $this;
    }

    public function getMedicalNote(): ?string
    {
        return $this->medicalNote;
    }

    public function setMedicalNote(string $medicalNote): static
    {
        $this->medicalNote = $medicalNote;

        return $this;
    }

    public function getDosage(): ?string
    {
        return $this->dosage;
    }

    public function setDosage(string $dosage): static
    {
        $this->dosage = $dosage;

        return $this;
    }

    public function getBiologicalData(): ?BiologicalData
    {
        return $this->biologicalData;
    }

    public function setBiologicalData(?BiologicalData $biologicalData): static
    {
        $this->biologicalData = $biologicalData;

        return $this;
    }
}
