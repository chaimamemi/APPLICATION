<?php

namespace App\Entity;

use App\Repository\BraceletRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BraceletRepository::class)]
class Bracelet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $identificationCode = null;

    #[ORM\OneToMany(mappedBy: 'bracelet', targetEntity: User::class)]
    private Collection $userId;

    #[ORM\ManyToOne(inversedBy: 'braceletId')]
    private ?BiologicalData $biologicalData = null;

    #[ORM\ManyToOne(inversedBy: 'braceletId')]
    private ?Alert $alert = null;

    #[ORM\Column(length: 255)]
    private ?string $temperature = null;

    #[ORM\Column(length: 255)]
    private ?string $bloodPressure = null;

    #[ORM\Column(length: 255)]
    private ?string $heartRate = null;

    #[ORM\Column(length: 255)]
    private ?string $movement = null;

    #[ORM\Column(length: 255)]
    private ?string $gps = null;

    #[ORM\Column(length: 255)]
    private ?string $latitude = null;

    #[ORM\Column(length: 255)]
    private ?string $longitude = null;

    public function __construct()
    {
        $this->userId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentificationCode(): ?string
    {
        return $this->identificationCode;
    }

    public function setIdentificationCode(string $identificationCode): static
    {
        $this->identificationCode = $identificationCode;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUserId(): Collection
    {
        return $this->userId;
    }

    public function addUserId(User $userId): static
    {
        if (!$this->userId->contains($userId)) {
            $this->userId->add($userId);
            $userId->setBracelet($this);
        }

        return $this;
    }

    public function removeUserId(User $userId): static
    {
        if ($this->userId->removeElement($userId)) {
            // set the owning side to null (unless already changed)
            if ($userId->getBracelet() === $this) {
                $userId->setBracelet(null);
            }
        }

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

    public function getAlert(): ?Alert
    {
        return $this->alert;
    }

    public function setAlert(?Alert $alert): static
    {
        $this->alert = $alert;

        return $this;
    }

    public function getTemperature(): ?string
    {
        return $this->temperature;
    }

    public function setTemperature(string $temperature): static
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getBloodPressure(): ?string
    {
        return $this->bloodPressure;
    }

    public function setBloodPressure(string $bloodPressure): static
    {
        $this->bloodPressure = $bloodPressure;

        return $this;
    }

    public function getHeartRate(): ?string
    {
        return $this->heartRate;
    }

    public function setHeartRate(string $heartRate): static
    {
        $this->heartRate = $heartRate;

        return $this;
    }

    public function getMovement(): ?string
    {
        return $this->movement;
    }

    public function setMovement(string $movement): static
    {
        $this->movement = $movement;

        return $this;
    }

    public function getGps(): ?string
    {
        return $this->gps;
    }

    public function setGps(string $gps): static
    {
        $this->gps = $gps;

        return $this;
    }
    public function __toString(): string
    {
        // Retourne l'identifiant du bracelet en tant que chaîne de caractères
        return (string) $this->id;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }
}
 