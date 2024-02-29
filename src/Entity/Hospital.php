<?php

namespace App\Entity;

use App\Repository\HospitalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HospitalRepository::class)]
class Hospital
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    private ?string $latitude = null;

    #[ORM\Column(length: 255)]
    private ?string $longitude = null;

   
    #[ORM\OneToMany(mappedBy: 'hospital', targetEntity: BiologicalData::class)]
    private Collection $biologicalData;
    
 

    public function __construct()
    {
        $this->biologicalData = new ArrayCollection();
       
    }

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
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

    
  

   

    /**
     * @return Collection<int, BiologicalData>
     */
    public function getBiologicalData(): Collection
    {
        return $this->biologicalData;
    }

    public function addBiologicalData(BiologicalData $biologicalData): static
    {
        if (!$this->biologicalData->contains($biologicalData)) {
            $this->biologicalData->add($biologicalData);
            $biologicalData->setHospital($this);
        }

        return $this;
    }

    public function removeBiologicalData(BiologicalData $biologicalData): static
    {
        if ($this->biologicalData->removeElement($biologicalData)) {
            // set the owning side to null (unless already changed)
            if ($biologicalData->getHospital() === $this) {
                $biologicalData->setHospital(null);
            }
        }

        return $this;
    }
    public function __toString(): string
{
    return $this->name;
}
}
