<?php
declare(strict_types=1);

namespace App\Response;

use App\Struct\SerializedVehicle;

class RedirectResponse extends HandledResponse
{
    public readonly string $url;

    /**
     * @param array<SerializedVehicle> $serializedVehicles
     */
    public function __construct(string $title, string $message, string $url){
        $this->url = $url;
        $this->responseType = 'redirect';
        parent::__construct($title, $message);
    }
}