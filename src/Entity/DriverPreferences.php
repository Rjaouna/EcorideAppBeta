<?php

namespace App\Entity;

use App\Repository\DriverPreferencesRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\TimestampableTrait;

#[ORM\Entity(repositoryClass: DriverPreferencesRepository::class)]
#[ORM\HasLifecycleCallbacks]
class DriverPreferences
{
    use TimestampableTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'driverPreferences', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?bool $smokingAllowed = null;

    #[ORM\Column]
    private ?bool $petsAllowed = null;

    #[ORM\Column(nullable: true)]
    private ?array $extras = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function isSmokingAllowed(): ?bool
    {
        return $this->smokingAllowed;
    }

    public function setSmokingAllowed(bool $smokingAllowed): static
    {
        $this->smokingAllowed = $smokingAllowed;

        return $this;
    }

    public function isPetsAllowed(): ?bool
    {
        return $this->petsAllowed;
    }

    public function setPetsAllowed(bool $petsAllowed): static
    {
        $this->petsAllowed = $petsAllowed;

        return $this;
    }

    public function getExtras(): array
    {
        return $this->extras ?? [];
    }
    public function setExtras(array $extras): self
    {
        $this->extras = array_values(array_unique($extras));
        return $this;
    }
}
