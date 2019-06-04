<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PrestationRepository")
 */
class Prestation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="time")
     */
    private $duration;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="prestations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Service", mappedBy="prestations")
     */
    private $services;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", mappedBy="prestations")
     */
    private $products;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Rendezvous", mappedBy="prestations")
     */
    private $rendezvous;

    public function __construct()
    {
        $this->services = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->rendezvous = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(\DateTimeInterface $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Service[]
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services[] = $service;
            $service->addPrestation($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->contains($service)) {
            $this->services->removeElement($service);
            $service->removePrestation($this);
        }

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addPrestation($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            $product->removePrestation($this);
        }

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(string $comments): self
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * @return Collection|Rendezvous[]
     */
    public function getRendezvous(): Collection
    {
        return $this->rendezvous;
    }

    public function addRendezvous(Rendezvous $rendezvous): self
    {
        if (!$this->rendezvous->contains($rendezvous)) {
            $this->rendezvous[] = $rendezvous;
            $rendezvous->addPrestation($this);
        }

        return $this;
    }

    public function removeRendezvous(Rendezvous $rendezvous): self
    {
        if ($this->rendezvous->contains($rendezvous)) {
            $this->rendezvous->removeElement($rendezvous);
            $rendezvous->removePrestation($this);
        }

        return $this;
    }
}
