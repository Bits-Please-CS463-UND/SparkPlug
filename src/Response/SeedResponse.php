<?php
declare(strict_types=1);

namespace App\Response;

use App\Struct\SerializedVehicle;

class SeedResponse extends HandledResponse
{
    /** @var array<SerializedVehicle> */
    public readonly array $vehicles;

    /**
     * @param array<SerializedVehicle> $serializedVehicles
     */
    public function __construct(string $title, string $message, array $serializedVehicles){
        $this->vehicles = $serializedVehicles;
        $this->responseType = 'seed';
        parent::__construct($title, $message);
    }
}