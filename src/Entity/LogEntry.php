<?php
declare(strict_types=1);

namespace App\Entity;

use App\Doctrine\UuidGenerator;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class LogEntry
{
    #[ORM\Id]
    #[ORM\Column(
        type: Types::STRING,
        length: 36,
        unique: true,
        nullable: false
    )]
    #[ORM\GeneratedValue(
        strategy: 'CUSTOM'
    )]
    #[ORM\CustomIdGenerator(
        class: UuidGenerator::class
    )]
    public string $id;

    #[ORM\Column]
    public DateTimeInterface $time;

    #[ORM\JoinColumn]
    #[ORM\ManyToOne(
        targetEntity: User::class,
    )]
    public User $driver;

    #[ORM\JoinColumn]
    #[ORM\ManyToOne(
        targetEntity: Vehicle::class,
    )]
    public Vehicle $vehicle;

    #[ORM\PrePersist]
    public function persist(): void{
        $this->time = new DateTimeImmutable();
    }

    #[ORM\Column(type: Types::STRING)]
    private string $title;

    #[ORM\Column(type: Types::TEXT)]
    private string $message;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeInterface $issuedAt;

    public function __construct(string $title, string $message)
    {
        $this->title = $title;
        $this->message = $message;
        $this->issuedAt = new \DateTimeImmutable();
    }


    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getIssuedAt(): \DateTimeInterface
    {
        return $this->issuedAt;
    }

    public function setIssuedAt(\DateTimeInterface $issuedAt): void
    {
        $this->issuedAt = $issuedAt;
    }
}