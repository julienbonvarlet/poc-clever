<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TradeInCartRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: TradeInCartRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource]
class TradeInCart
{
    #[ORM\Id]
    #[ORM\Column]
    private string $id;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['tradeincart:read'])]
    private Brand $brand;

    #[ORM\ManyToOne(inversedBy: 'tradeInCarts')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?Customer $customer;

    #[ORM\Column(unique: true)]
    private string $reference;

    #[ORM\Column]
    private string $state = 'draft';

    #[ORM\Column]
    private string $channel;

    #[ORM\Column(nullable: true)]
    #[Groups(['tradeincart:read'])]
    private ?string $shippingVoucher = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['tradeincart:read'])]
    private ?string $transfertVoucher = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $validatedAt = null;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    #[ORM\Column]
    private DateTimeImmutable $updatedAt;

    /**
     * @var Collection<int, TradeIn>
     */
    #[ORM\OneToMany(targetEntity: TradeIn::class, mappedBy: 'tradeInCart', orphanRemoval: true)]
    #[Groups(['tradeincart:read', 'tradein:link'])]
    private Collection $items;

    public function __construct(
        Brand $brand,
        ?Customer $customer,
        string $channel,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null,
    ) {
        $this->id = uuid_create();
        $this->brand = $brand;
        $this->customer = $customer;
        $this->channel = $channel;
        $this->reference = uuid_create();
        $this->createdAt = $createdAt ?? new DateTimeImmutable();
        $this->updatedAt = $updatedAt ?? new DateTimeImmutable();
        $this->items = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getBrand(): Brand
    {
        return $this->brand;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer): void
    {
        $this->customer = $customer;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function getShippingVoucher(): ?string
    {
        return $this->shippingVoucher;
    }

    public function getTransfertVoucher(): ?string
    {
        return $this->transfertVoucher;
    }

    public function getValidatedAt(): ?DateTimeImmutable
    {
        return $this->validatedAt;
    }

    public function setValidatedAt(DateTimeImmutable $validatedAt): void
    {
        $this->validatedAt = $validatedAt;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    /**
     * @return Collection<int, TradeIn>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }
}
