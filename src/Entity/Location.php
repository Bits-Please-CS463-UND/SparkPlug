<?php
declare(strict_types=1);

namespace App\Entity;

use App\Doctrine\UuidGenerator;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class Location
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

    #[ORM\Column(
        type: Types::FLOAT,
        precision: 6
    )]
    public float $longitude;

    #[ORM\Column(
        type: Types::FLOAT,
        precision: 6
    )]
    public float $latitude;

    #[ORM\Column(
        type: Types::DATETIME_IMMUTABLE,
    )]
    public DateTimeInterface $createdAt;

    public function __construct(){
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLng(): float
    {
        return $this->longitude;
    }

    public function setLng(float $longitude): self
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function getLat(): float
    {
        return $this->latitude;
    }

    public function setLat(float $latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }
}