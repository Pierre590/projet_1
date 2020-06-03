<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RideRepository")
 */
class Ride
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $schedule;

    /**
     * @ORM\Column(type="integer")
     */
    private $spaceAvailable;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $observations;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="rides")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="rides")
     */
    private $company;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\City", inversedBy="rides")
     */
    private $departure;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\City", inversedBy="rides")
     */
    private $arrival;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Resa", mappedBy="ride", orphanRemoval=true)
     */
    private $resas;

    public function __construct()
    {
        $this->resas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSchedule(): ?\DateTimeInterface
    {
        return $this->schedule;
    }

    public function setSchedule(\DateTimeInterface $schedule): self
    {
        $this->schedule = $schedule;

        return $this;
    }

    public function getSpaceAvailable(): ?int
    {
        return $this->spaceAvailable;
    }

    public function setSpaceAvailable(int $spaceAvailable): self
    {
        $this->spaceAvailable = $spaceAvailable;

        return $this;
    }

    public function getObservations(): ?string
    {
        return $this->observations;
    }

    public function setObservations(?string $observations): self
    {
        $this->observations = $observations;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getDeparture(): ?City
    {
        return $this->departure;
    }

    public function setDeparture(?City $departure): self
    {
        $this->departure = $departure;

        return $this;
    }

    public function getArrival(): ?City
    {
        return $this->arrival;
    }

    public function setArrival(?City $arrival): self
    {
        $this->arrival = $arrival;

        return $this;
    }

    /**
     * @return Collection|Resa[]
     */
    public function getResas(): Collection
    {
        return $this->resas;
    }

    public function addResa(Resa $resa): self
    {
        if (!$this->resas->contains($resa)) {
            $this->resas[] = $resa;
            $resa->setRide($this);
        }

        return $this;
    }

    public function removeResa(Resa $resa): self
    {
        if ($this->resas->contains($resa)) {
            $this->resas->removeElement($resa);
            // set the owning side to null (unless already changed)
            if ($resa->getRide() === $this) {
                $resa->setRide(null);
            }
        }

        return $this;
    }
}
