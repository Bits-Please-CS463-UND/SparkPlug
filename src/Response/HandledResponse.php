<?php
declare(strict_types=1);

namespace App\Response;

class HandledResponse
{
    public string $title;
    public string $message;
    public readonly string $sentinel;
    public string $responseType;

    public function __construct(string $title, string $message)
    {
        $this->title = $title;
        $this->message = $message;
        $this->sentinel = 'omg haiiiiiii :3';
        $this->responseType ??= 'default';
    }
}