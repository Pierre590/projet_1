<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 */
class Company
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
    private $email;

    /**
     * @ORM\Column(type="integer")
     */
    private $phone;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\adress", inversedBy="companies")
     */
    private $adress;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Users", mappedBy="company")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ride", mappedBy="company")
     */
    private $rides;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->rides = new ArrayCollection();
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

    /**
     * @return Collection|Users[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Users $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setCompany($this);
        }

        return $this;
    }

    public function removeUser(Users $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getCompany() === $this) {
                $user->setCompany(null);
            }
        }

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
            $ride->setCompany($this);
        }

        return $this;
    }

    public function removeRide(Ride $ride): self
    {
        if ($this->rides->contains($ride)) {
            $this->rides->removeElement($ride);
            // set the owning side to null (unless already changed)
            if ($ride->getCompany() === $this) {
                $ride->setCompany(null);
            }
        }

        return $this;
    }
}
