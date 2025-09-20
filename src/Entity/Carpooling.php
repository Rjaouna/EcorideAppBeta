<?php

namespace App\Entity;

use App\Repository\CarpoolingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarpoolingRepository::class)]
class Carpooling
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'carpoolings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $driver = null;

    #[ORM\ManyToOne(inversedBy: 'carpoolings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Vehicle $vehicle = null;

    #[ORM\Column(length: 50)]
    private ?string $originCity = null;

    #[ORM\Column(length: 50)]
    private ?string $destinationCity = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $departureAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $arrivalAt = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $seatsTotal = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $seatsAvailable = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $priceCredits = null;

    #[ORM\Column(length: 20)]
    private ?string $status = null;

    #[ORM\Column]
    private ?bool $ecoTag = null;

    #[ORM\Column(nullable: true)]
    private ?int $durationMinutes = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDriver(): ?User
    {
        return $this->driver;
    }

    public function setDriver(?User $driver): static
    {
        $this->driver = $driver;

        return $this;
    }

    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicle $vehicle): static
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    public function getOriginCity(): ?string
    {
        return $this->originCity;
    }

    public function setOriginCity(string $originCity): static
    {
        $this->originCity = $originCity;

        return $this;
    }

    public function getDestinationCity(): ?string
    {
        return $this->destinationCity;
    }

    public function setDestinationCity(string $destinationCity): static
    {
        $this->destinationCity = $destinationCity;

        return $this;
    }

    public function getDepartureAt(): ?\DateTimeImmutable
    {
        return $this->departureAt;
    }

    public function setDepartureAt(\DateTimeImmutable $departureAt): static
    {
        $this->departureAt = $departureAt;

        return $this;
    }

    public function getArrivalAt(): ?\DateTimeImmutable
    {
        return $this->arrivalAt;
    }

    public function setArrivalAt(\DateTimeImmutable $arrivalAt): static
    {
        $this->arrivalAt = $arrivalAt;

        return $this;
    }

    public function getSeatsTotal(): ?int
    {
        return $this->seatsTotal;
    }

    public function setSeatsTotal(int $seatsTotal): static
    {
        $this->seatsTotal = $seatsTotal;

        return $this;
    }

    public function getSeatsAvailable(): ?int
    {
        return $this->seatsAvailable;
    }

    public function setSeatsAvailable(int $seatsAvailable): static
    {
        $this->seatsAvailable = $seatsAvailable;

        return $this;
    }

    public function getPriceCredits(): ?int
    {
        return $this->priceCredits;
    }

    public function setPriceCredits(int $priceCredits): static
    {
        $this->priceCredits = $priceCredits;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function isEcoTag(): ?bool
    {
        return $this->ecoTag;
    }

    public function setEcoTag(bool $ecoTag): static
    {
        $this->ecoTag = $ecoTag;

        return $this;
    }

    public function getDurationMinutes(): ?int
    {
        return $this->durationMinutes;
    }

    public function setDurationMinutes(?int $durationMinutes): static
    {
        $this->durationMinutes = $durationMinutes;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
