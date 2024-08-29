<?php

namespace app\contracts;

use app\dto\NotificationDTO;
use app\enums\NotifySenderTypesEnum;
use app\models\Subscriber;

interface NotificationSenderInterface
{
    public function getType(): NotifySenderTypesEnum;

    /**
     * @param Subscriber[] $recipients
     * @param NotificationDTO $notification
     * @return void
     */
    public function send(array $recipients, NotificationDTO $notification): void;
}