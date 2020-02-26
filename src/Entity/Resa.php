<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ResaRepository")
 */
class Resa
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\users", inversedBy="resas")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ride", inversedBy="resas")
     */
    private $ride;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?users
    {
        return $this->user;
    }

    public function setUser(?users $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getRide(): ?ride
    {
        return $this->ride;
    }

    public function setRide(?ride $ride): self
    {
        $this->ride = $ride;

        return $this;
    }
}
