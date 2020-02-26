<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsersRepository")
 */
class Users
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
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="integer")
     */
    private $phone;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\adress", cascade={"persist", "remove"})
     */
    private $adress;

    /**
     * @ORM\Column(type="integer")
     */
    private $googleId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\company", inversedBy="users")
     */
    private $company;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ride", mappedBy="user")
     */
    private $rides;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Resa", mappedBy="user")
     */
    private $resas;

    public function __construct()
    {
        $this->rides = new ArrayCollection();
        $this->resas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAdress(): ?adress
    {
        return $this->adress;
    }

    public function setAdress(?adress $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getGoogleId(): ?int
    {
        return $this->googleId;
    }

    public function setGoogleId(int $googleId): self
    {
        $this->googleId = $googleId;

        return $this;
    }

    public function getCompany(): ?company
    {
        return $this->company;
    }

    public function setCompany(?company $company): self
    {
        $this->company = $company;

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
            $ride->setUser($this);
        }

        return $this;
    }

    public function removeRide(Ride $ride): self
    {
        if ($this->rides->contains($ride)) {
            $this->rides->removeElement($ride);
            // set the owning side to null (unless already changed)
            if ($ride->getUser() === $this) {
                $ride->setUser(null);
            }
        }

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
            $resa->setUser($this);
        }

        return $this;
    }

    public function removeResa(Resa $resa): self
    {
        if ($this->resas->contains($resa)) {
            $this->resas->removeElement($resa);
            // set the owning side to null (unless already changed)
            if ($resa->getUser() === $this) {
                $resa->setUser(null);
            }
        }

        return $this;
    }
}
