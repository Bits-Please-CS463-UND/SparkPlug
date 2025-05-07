<?php
declare(strict_types=1);

namespace App\Entity;

use App\Doctrine\UuidGenerator;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "app_user")]
class User
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

    #[ORM\OneToMany(
        targetEntity: Vehicle::class,
        mappedBy: 'owner',
    )]
    public Collection $ownedVehicles;

    #[ORM\ManyToMany(
        targetEntity: Vehicle::class,
        inversedBy: 'drivers'
    )]
    #[ORM\JoinTable(
        name: 'user_shared_vehicles',
    )]
    public Collection $sharedVehicles;

    #[ORM\Column]
    public string $firstName;

    #[ORM\Column]
    public string $lastName;

    #[ORM\Column]
    public string $email;

    #[ORM\Column]
    public string $phoneNum;
}