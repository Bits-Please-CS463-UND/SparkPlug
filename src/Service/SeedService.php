<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Entity\Vehicle;
use App\Response\SeedResponse;
use App\Struct\SerializedUser;
use App\Struct\SerializedVehicle;

class SeedService
{
    public function generate(User $user): SeedResponse {
        return new SeedResponse(
            "YOU GOT HTIS BOSSHOG",
            "typescript please do something with this",
            array_map(
                function (Vehicle $vehicle){
                    return new SerializedVehicle($vehicle);
                },
                $user->sharedVehicles->toArray()
            ),
            new SerializedUser($user)
        );
    }
}