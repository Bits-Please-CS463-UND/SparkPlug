<?php
declare(strict_types=1);

namespace App\Entity;

use App\Doctrine\UuidGenerator;
use App\Enum\NotificationPriority;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class Notification
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
    public string $title;

    #[ORM\Column]
    public string $message;

    #[ORM\Column]
    public NotificationPriority $priority;

    #[ORM\Column]
    public DateTimeInterface $issuedAt;

    #[ORM\Column]
    public bool $acknowledged = false;

    #[ORM\JoinColumn]
    #[ORM\ManyToOne(
        targetEntity: Vehicle::class,
        inversedBy: 'notifications',
    )]
    public Vehicle $vehicle;

    #[ORM\PrePersist]
    public function persist(): void{
        $this->issuedAt = new DateTimeImmutable();
    }
}