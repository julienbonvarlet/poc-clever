<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CustomerRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
#[ORM\UniqueConstraint(fields: ['brand', 'email', 'phone'])]
#[ORM\HasLifecycleCallbacks]
#[ApiResource]
class Customer implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column]
    private string $id;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['customer:read'])]
    private Brand $brand;

    #[ORM\Column]
    private string $firstName;

    #[ORM\Column]
    private string $lastName;

    #[ORM\Column]
    private string $email;

    #[ORM\Column]
    private string $phone;

    #[ORM\Column]
    private bool $emailSubscription;

    #[ORM\Column]
    private bool $smsSubscription;

    // Nullable because user imported from shopify won't have a password - we  don't have the data
    #[ORM\Column(length: 60, nullable: true)]
    private ?string $password;

    #[ORM\Column(length: 64)]
    #[Groups(['customer:read'])]
    private string $apiToken;

    #[ORM\Column]
    private bool $enabled;

    #[ORM\Column(nullable: true)]
    private ?string $gender;

    #[ORM\Column(nullable: true)]
    private ?string $birthdate;

    #[ORM\Column(length: 2)]
    private string $countryCode;

    #[ORM\Column(length: 5)]
    private string $locale;

    #[ORM\Column]
    private int $walletAmount = 0;

    #[ORM\Column(length: 3)]
    private string $currency;

    #[ORM\Column]
    private bool $isGuest;

    #[ORM\Column]
    private array $metadata = [];

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    #[ORM\Column]
    private DateTimeImmutable $updatedAt;

    public function __construct(
        Brand $brand,
        ?string $gender,
        string $firstName,
        string $lastName,
        string $email,
        string $phone,
        ?string $birthdate,
        string $countryCode,
        string $currency,
        string $locale,
        ?bool $emailSubscription = false,
        ?bool $smsSubscription = false,
        ?string $password = null,
        bool $enabled = false,
        bool $isGuest = false,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null,
    ) {
        $this->id = uuid_create();
        $this->apiToken = bin2hex(random_bytes(32));
        $this->brand = $brand;
        $this->gender = $gender;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->phone = $phone;
        $this->password = $password;
        $this->birthdate = $birthdate;
        $this->countryCode = $countryCode;
        $this->currency = $currency;
        $this->locale = $locale;
        $this->emailSubscription = $emailSubscription;
        $this->smsSubscription = $smsSubscription;
        $this->enabled = $enabled;
        $this->isGuest = $isGuest;
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

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getEmailSubscription(): bool
    {
        return $this->emailSubscription;
    }

    public function setEmailSubscription(bool $emailSubscription): void
    {
        $this->emailSubscription = $emailSubscription;
    }

    public function getSmsSubscription(): bool
    {
        return $this->smsSubscription;
    }

    public function getApiToken(): string
    {
        return $this->apiToken;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
        $this->enabled = true;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function getBirthdate(): ?string
    {
        return $this->birthdate;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function isGuest(): bool
    {
        return $this->isGuest;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function getWalletAmount(): int
    {
        return $this->walletAmount;
    }

    public function setWalletAmount(int $walletAmount): void
    {
        $this->walletAmount = $walletAmount;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function setMetadata(array $metadata): void
    {
        $this->metadata = $metadata;
    }

    public function addMetadata(string $key, mixed $value): void
    {
        $this->metadata[$key] = $value;
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

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return ['ROLE_API_CUSTOMER'];
    }

    public function eraseCredentials(): void
    {
    }
}
