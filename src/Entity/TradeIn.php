<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TradeInRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TradeInRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource]
class TradeIn
{
    #[ORM\Id]
    #[ORM\Column]
    private string $id;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Brand $brand;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private TradeInCart $tradeInCart;

    #[ORM\Column(unique: true)]
    private string $reference;

    #[ORM\Column]
    private string $ean;

    #[ORM\Column]
    private string $sku;

    #[ORM\Column]
    private string $color;

    #[ORM\Column]
    private string $size;

    #[ORM\Column(name: '`condition`')]
    private string $condition;

    #[ORM\Column]
    private int $priceOrigin;

    #[ORM\Column]
    private int $priceResale;

    #[ORM\Column]
    private int $priceOffer;

    #[ORM\Column(length: 3)]
    private string $currency;

    #[ORM\Column]
    private string $state = 'draft';

    #[ORM\Column]
    private string $slug;

    #[ORM\Column]
    private array $metadata;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    #[ORM\Column]
    private DateTimeImmutable $updatedAt;

    public function __construct(
        Brand $brand,
        TradeInCart $tradeInCart,
        string $ean,
        string $sku,
        string $color,
        string $size,
        string $condition,
        int $priceOrigin,
        int $priceResale,
        int $priceOffer,
        string $currency,
        string $slug,
        array $metadata,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null,
    ) {
        $this->id = uuid_create();
        $this->brand = $brand;
        $this->tradeInCart = $tradeInCart;
        $this->reference = uuid_create();
        $this->ean = $ean;
        $this->sku = $sku;
        $this->color = $color;
        $this->size = $size;
        $this->condition = $condition;
        $this->priceOrigin = $priceOrigin;
        $this->priceResale = $priceResale;
        $this->priceOffer = $priceOffer;
        $this->currency = $currency;
        $this->slug = $slug;
        $this->metadata = $metadata;
        $this->createdAt = $createdAt ?? new DateTimeImmutable();
        $this->updatedAt = $updatedAt ?? new DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getBrand(): Brand
    {
        return $this->brand;
    }

    public function getTradeInCart(): TradeInCart
    {
        return $this->tradeInCart;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function getEan(): string
    {
        return $this->ean;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function getSize(): string
    {
        return $this->size;
    }

    public function getCondition(): string
    {
        return $this->condition;
    }

    public function getPriceOrigin(): int
    {
        return $this->priceOrigin;
    }

    public function getPriceResale(): int
    {
        return $this->priceResale;
    }

    public function getPriceOffer(): int
    {
        return $this->priceOffer;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function setMetadata(array $metadata): void
    {
        $this->metadata = $metadata;
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
}
