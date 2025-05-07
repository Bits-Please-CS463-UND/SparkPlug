<?php
declare(strict_types=1);

namespace App\Entity;

use App\Doctrine\UuidGenerator;
use App\Repository\VehicleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehicleRepository::class)]
class Vehicle
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

    #[ORM\ManyToOne(
        targetEntity: User::class,
        inversedBy: 'ownedVehicles',
    )]
    #[ORM\JoinColumn(
        name: 'owner_id',
        referencedColumnName: 'id',
    )]
    public User $owner;

    #[ORM\ManyToMany(
        targetEntity: User::class,
        mappedBy: 'sharedVehicles',
    )]
    public Collection $drivers;

    #[ORM\Column]
    public string $vin;

    #[ORM\Column]
    public string $licensePlates;

    #[ORM\Column]
    public string $make;

    #[ORM\Column]
    public string $model;

    #[ORM\Column]
    public int $year;

    #[ORM\Column]
    public string $color;

    #[ORM\OneToMany(
        targetEntity: Notification::class,
        mappedBy: 'vehicle',
        cascade: ['remove'],
    )]
    public Collection $notifications;

    #[ORM\ManyToOne(targetEntity: Location::class, cascade: ['remove'])]
    #[ORM\OrderBy(['createdAt' => 'DESC'])]
    public Collection $locations;

    #[ORM\OneToOne(targetEntity: Geofence::class, cascade: ['remove'], orphanRemoval: true)]
    public ?Geofence $geofence = null;

    public function __construct(){
        $this->notifications = new ArrayCollection();
        $this->locations = new ArrayCollection();
        $this->drivers = new ArrayCollection();
    }
}