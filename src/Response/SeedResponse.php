<?php
declare(strict_types=1);

namespace App\Response;

use App\Entity\User;
use App\Struct\SerializedUser;
use App\Struct\SerializedVehicle;

class SeedResponse extends HandledResponse
{
    /** @var array<SerializedVehicle> */
    public readonly array $vehicles;

    public readonly SerializedUser $profile;

    /**
     * @param array<SerializedVehicle> $serializedVehicles
     */
    public function __construct(string $title, string $message, array $serializedVehicles, SerializedUser $serializedUser){
        $this->vehicles = $serializedVehicles;
        $this->profile = $serializedUser;
        $this->responseType = 'seed';
        parent::__construct($title, $message);
    }
}