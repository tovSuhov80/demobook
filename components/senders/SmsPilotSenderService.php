<?php

namespace app\components\senders;

use app\contracts\NotificationSenderInterface;
use app\dto\NotificationDTO;
use app\enums\NotifySenderTypesEnum;
use SMSPilot;
use Yii;
require_once('smspilot.class.php');
class SmsPilotSenderService implements NotificationSenderInterface
{

    private SMSPilot $smsPilot;

    public function __construct()
    {
        $this->smsPilot = new SMSPilot($_ENV['SMSPILOT_KEY']);
    }

    public function getType(): NotifySenderTypesEnum
    {
        return NotifySenderTypesEnum::SmsPilot;
    }

    /**
     * @inheritDoc
     */
    public function send(array $recipients, NotificationDTO $notification): void
    {
        //В реальных условиях здесь формируется задача для очереди, чтобы выполнить отправку асинхронно,
        //но в рамках тестового задания осуществляем отправку в основном потоке.
        $sendedCount = 0;
        foreach ($recipients as $recipient) {
            if (false !== $this->smsPilot->send($this->cleanPhoneNumber($recipient->phone), $notification->getBody())) {
                $sendedCount++;
            }
        }
        Yii::info("Sending sms pilot notifications: {$sendedCount}");
        Yii::$app->session->setFlash('info', "Отправлено смс-уведомлений: {$sendedCount}");
    }

    private function cleanPhoneNumber($phoneNumber): string
    {
        return preg_replace('/\D/', '', $phoneNumber);
    }

}