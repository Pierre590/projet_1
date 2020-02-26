<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CityRepository")
 */
class City
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\zip", inversedBy="cities")
     */
    private $zip;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ride", mappedBy="departure")
     */
    private $rides;

    public function __construct()
    {
        $this->rides = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getZip(): ?zip
    {
        return $this->zip;
    }

    public function setZip(?zip $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * @return Collection|Ride[]
     */
    public function getRides(): Collection
    {
        return $this->rides;
    }

    public function addRide(Ride $ride): self
    {
        if (!$this->rides->contains($ride)) {
            $this->rides[] = $ride;
            $ride->setDeparture($this);
        }

        return $this;
    }

    public function removeRide(Ride $ride): self
    {
        if ($this->rides->contains($ride)) {
            $this->rides->removeElement($ride);
            // set the owning side to null (unless already changed)
            if ($ride->getDeparture() === $this) {
                $ride->setDeparture(null);
            }
        }

        return $this;
    }
}
