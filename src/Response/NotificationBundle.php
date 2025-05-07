<?php
declare(strict_types=1);

namespace App\Response;

use App\Struct\SerializedNotification;

class NotificationBundle extends HandledResponse
{
    /** @var array<SerializedNotification */
    public readonly array $notifications;

    public function __construct(string $title, string $message, array $serializedNotifications){
        $this->notifications = $serializedNotifications;
        $this->responseType = 'notifications';
        parent::__construct($title, $message);
    }
}