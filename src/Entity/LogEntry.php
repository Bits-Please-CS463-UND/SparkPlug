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
}