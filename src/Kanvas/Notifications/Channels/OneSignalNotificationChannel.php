<?php

declare(strict_types=1);

namespace Kanvas\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Kanvas\Apps\Models\Apps;
use Kanvas\Notifications\Services\OneSignalService;
use Kanvas\Users\Models\Users;

class OneSignalNotificationChannel
{
    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        $oneSignalMessage = $notification->toOneSignal($notifiable);

        if (empty($oneSignalMessage)) {
            return;
        }

        $app = Apps::getById($oneSignalMessage['apps_id']);

        $oneSignalService = new OneSignalService($app);

        $oneSignalService->sendNotificationToUser(
            $oneSignalMessage['message'],
            Users::getById($oneSignalMessage['user_id']),
            $url = null,
            $data = null,
            $buttons = null,
            $schedule = null,
            $oneSignalMessage['subtitle'],
            $oneSignalMessage['title']
        );
    }
}
