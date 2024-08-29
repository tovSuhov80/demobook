<?php

namespace app\components;

use app\contracts\NotificationSenderInterface;
use app\dto\NotificationDTO;
use app\enums\NotifySenderTypesEnum;
use Exception;
use app\models\Subscriber;
use yii\base\Component;

class Notificator extends Component
{
    /** @var NotificationSenderInterface[]  */
    protected array $senders = [];


    public function setSenders(array $senders): self
    {
        foreach ($senders as $senderClass) {
            $this->senders[] = new $senderClass();
        }
        return $this;
    }

    /**
     * @param Subscriber[] $recipients
     * @param string $body
     * @param string|null $subject
     * @return void
     * @throws Exception
     */
    public function send(NotifySenderTypesEnum $sendType, array $recipients, string $body, ?string $subject = null): void
    {
        if (empty($recipients)) return;

        $notifyDTO = new NotificationDTO(
            body: $body,
            subject: $subject,
        );

        foreach ($this->senders as $sender) {
            if (!($sender instanceof NotificationSenderInterface) || $sender->getType() !== $sendType) {
                continue;
            }

            $sender->send($recipients, $notifyDTO);
        }
    }
}
