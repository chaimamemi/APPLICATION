<?php

namespace App\Entity;

use App\Repository\BiologicalDataRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BiologicalDataRepository::class)]
class BiologicalData
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'biologicalData')]
    private ?Bracelet $bracelet;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $timestamp = null;

    #[ORM\Column(length: 255)]
    private ?string $measurementType = null;

    #[ORM\Column(length: 255)]
    private ?string $value = null;

    #[ORM\ManyToOne(inversedBy: 'biologicalData')]
    private ?User $patient = null;

    #[ORM\Column(length: 255)]
    private ?string $patientName = null;

    #[ORM\Column(length: 255)]
    private ?string $PatientLastName = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $patientAge = null;

    #[ORM\OneToMany(mappedBy: 'biologicalData', targetEntity: Medication::class)]
    private Collection $medication;

    #[ORM\Column(length: 255)]
    private ?string $disease = null;

    #[ORM\Column(length: 255)]
    private ?string $otherInformation = null;

    #[ORM\ManyToOne(inversedBy: 'biologicalData')]
    private ?Hospital $hospital = null;

   

    public function __construct()
    {
        $this->medication = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBracelet(): ?Bracelet
    {
        return $this->bracelet;
    }

    public function setBracelet(?Bracelet $bracelet): static
    {
        $this->bracelet = $bracelet;

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

    public function getMeasurementType(): ?string
    {
        return $this->measurementType;
    }

    public function setMeasurementType(string $measurementType): static
    {
        $this->measurementType = $measurementType;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getPatient(): ?User
    {
        return $this->patient;
    }

    public function setPatient(?User $patient): static
    {
        $this->patient = $patient;

        return $this;
    }

    public function getPatientName(): ?string
    {
        return $this->patientName;
    }

    public function setPatientName(string $patientName): static
    {
        $this->patientName = $patientName;

        return $this;
    }

    public function getPatientLastName(): ?string
    {
        return $this->PatientLastName;
    }

    public function setPatientLastName(string $PatientLastName): static
    {
        $this->PatientLastName = $PatientLastName;

        return $this;
    }

    public function getPatientAge(): ?int
    {
        return $this->patientAge;
    }

    public function setPatientAge(int $patientAge): static
    {
        $this->patientAge = $patientAge;

        return $this;
    }

    public function getMedication(): Collection
    {
        return $this->medication;
    }

    public function addMedication(Medication $medication): static
    {
        if (!$this->medication->contains($medication)) {
            $this->medication->add($medication);
            $medication->setBiologicalData($this);
        }

        return $this;
    }

    public function removeMedication(Medication $medication): static
    {
        if ($this->medication->removeElement($medication)) {
            // set the owning side to null (unless already changed)
            if ($medication->getBiologicalData() === $this) {
                $medication->setBiologicalData(null);
            }
        }

        return $this;
    }

    public function getDisease(): ?string
    {
        return $this->disease;
    }

    public function setDisease(string $disease): static
    {
        $this->disease = $disease;

        return $this;
    }

    public function getOtherInformation(): ?string
    {
        return $this->otherInformation;
    }

    public function setOtherInformation(string $otherInformation): static
    {
        $this->otherInformation = $otherInformation;

        return $this;
    }

    public function __toString(): string
    {
        return "ID: " . $this->id .
               ", Timestamp: " . ($this->timestamp ? $this->timestamp->format('Y-m-d H:i:s') : 'N/A') .
               ", Measurement Type: " . $this->measurementType .
               ", Value: " . $this->value .
               ", Patient Name: " . $this->patientName .
               ", Patient Last Name: " . $this->PatientLastName .
               ", Patient Age: " . $this->patientAge .
               ", Disease: " . $this->disease .
               ", Other Information: " . $this->otherInformation;
    }

    
    public function removePatient(User $patient): static
    {
        if ($this->patient === $patient) {
            $this->patient = null;
            // Optionally add additional logic here to update both sides of the relationship if necessary
        }
        
        return $this;
    }

    public function getHospital(): ?Hospital
    {
        return $this->hospital;
    }

    public function setHospital(?Hospital $hospital): static
    {
        $this->hospital = $hospital;

        return $this;
    }
}
