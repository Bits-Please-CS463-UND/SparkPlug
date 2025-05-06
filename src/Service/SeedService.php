<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Notification;
use App\Entity\User;
use App\Entity\Vehicle;
use App\Response\SeedResponse;
use App\Struct\SerializedNotification;
use App\Struct\SerializedUser;
use App\Struct\SerializedVehicle;

class SeedService
{
    public function generate(User $user): SeedResponse {
        $notifications = [];
        foreach($user->sharedVehicles as $vehicle){
            /** @var Vehicle $vehicle */
            $notifications += array_map(
                function (Notification $notification){
                    return new SerializedNotification($notification);
                },
                $vehicle->notifications->filter(function (Notification $n) { return !$n->acknowledged; })->toArray()
            );
        }

        return new SeedResponse(
            "YOU GOT HTIS BOSSHOG",
            "typescript please do something with this",
            array_map(
                function (Vehicle $vehicle){
                    return new SerializedVehicle($vehicle);
                },
                $user->sharedVehicles->toArray()
            ),
            new SerializedUser($user),
            $notifications
        );
    }
}