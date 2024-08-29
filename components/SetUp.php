<?php

namespace app\components;

use app\components\events\BookAddedEvent;
use Yii;
use yii\base\BootstrapInterface;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        $app->set('eventDispatcher', static function () {
            $dispatcher = new EventDispatcher();


            //Добавлена новая книга
            $dispatcher->on(BookAddedEvent::class, static function (BookAddedEvent $event): void {
                Yii::info('Book #' . $event->book->id . 'was added', 'app');
                //инициируем отправку уведомлений
                Yii::$app->subscribeService->sendNewBookNotifications($event->book);
            });

            return $dispatcher;
        });
    }
}
