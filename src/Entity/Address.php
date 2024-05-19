<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'addresses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\Column]
    private ?int $streetNumber = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $street = null;

    #[ORM\Column]
    private ?int $zipcode = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'billingAddress')]
    private Collection $ordersAsBilling;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'deliveryAddress')]
    private Collection $ordersAsDelivery;

    public function __construct()
    {
        $this->ordersAsBilling = new ArrayCollection();
        $this->ordersAsDelivery = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getStreetNumber(): ?int
    {
        return $this->streetNumber;
    }

    public function setStreetNumber(int $streetNumber): static
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getZipcode(): ?int
    {
        return $this->zipcode;
    }

    public function setZipcode(int $zipcode): static
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrdersAsBilling(): Collection
    {
        return $this->ordersAsBilling;
    }

    public function addOrdersAsBilling(Order $ordersAsBilling): static
    {
        if (!$this->ordersAsBilling->contains($ordersAsBilling)) {
            $this->ordersAsBilling->add($ordersAsBilling);
            $ordersAsBilling->setBillingAddress($this);
        }

        return $this;
    }

    public function removeOrdersAsBilling(Order $ordersAsBilling): static
    {
        if ($this->ordersAsBilling->removeElement($ordersAsBilling)) {
            // set the owning side to null (unless already changed)
            if ($ordersAsBilling->getBillingAddress() === $this) {
                $ordersAsBilling->setBillingAddress(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrdersAsDelivery(): Collection
    {
        return $this->ordersAsDelivery;
    }

    public function addOrdersAsDelivery(Order $ordersAsDelivery): static
    {
        if (!$this->ordersAsDelivery->contains($ordersAsDelivery)) {
            $this->ordersAsDelivery->add($ordersAsDelivery);
            $ordersAsDelivery->setDeliveryAddress($this);
        }

        return $this;
    }

    public function removeOrdersAsDelivery(Order $ordersAsDelivery): static
    {
        if ($this->ordersAsDelivery->removeElement($ordersAsDelivery)) {
            // set the owning side to null (unless already changed)
            if ($ordersAsDelivery->getDeliveryAddress() === $this) {
                $ordersAsDelivery->setDeliveryAddress(null);
            }
        }

        return $this;
    }
}
