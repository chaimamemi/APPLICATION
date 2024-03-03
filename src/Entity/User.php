<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $role = 'ROLE_USER'; // Set default role to ROLE_USER

    #[ORM\ManyToOne(inversedBy: 'userId')]
    private ?Bracelet $bracelet = null;

    #[ORM\OneToMany(mappedBy: 'patientUserId', targetEntity: Appointment::class)]
    private Collection $appointments;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: BiologicalData::class)]
    private Collection $biologicalData;

    #[ORM\Column(length: 255)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $speciality = null;

   
  

    public function __construct()
{
    $this->appointments = new ArrayCollection();
    $this->biologicalData = new ArrayCollection();
}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }
    
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }
    
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role ? 'ROLE_' . strtoupper($this->role) : null;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
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

    

    public function getAppointments(): Collection
    {
        return $this->appointments;
    }

    public function addAppointment(Appointment $appointment): static
    {
        if (!$this->appointments->contains($appointment)) {
            $this->appointments->add($appointment);
          
        }

        return $this;
    }

  

    public function getUsername()
    {
        return $this->email;
    }

    public function getSalt()
    {
        return null;
    }

    public function getRoles()
    {
        return [$this->role];
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getBiologicalData(): Collection
    {
        return $this->biologicalData;
    }

    public function addBiologicalData(BiologicalData $biologicalData): static
    {
        if (!$this->biologicalData->contains($biologicalData)) {
            $this->biologicalData->add($biologicalData);
            $biologicalData->setPatient($this);
        }

        return $this;
    }

    public function removeBiologicalData(BiologicalData $biologicalData): static
    {
        if ($this->biologicalData->removeElement($biologicalData)) {
            if ($biologicalData->getPatient() === $this) {
                $biologicalData->setPatient(null);
            }
        }

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getSpeciality(): ?string
    {
        return $this->speciality;
    }

    public function setSpeciality(string $speciality): static
    {
        $this->speciality = $speciality;

        return $this;
    }


    

}
