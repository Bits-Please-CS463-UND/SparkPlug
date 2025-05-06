<?php
declare(strict_types=1);

namespace App\Struct;

use App\Entity\Notification;

class SerializedNotification
{
    public readonly string $id;
    public readonly string $title;
    public readonly string $message;
    public readonly string $priority;
    public function __construct(
        Notification $notification
    ){
        $this->id = $notification->id;
        $this->title = $notification->title;
        $this->message = $notification->message;
        $this->priority = $notification->priority->value;
    }
}