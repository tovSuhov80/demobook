<?php

namespace app\services;

use app\enums\NotifySenderTypesEnum;
use app\models\Author;
use app\models\Book;
use app\models\Subscriber;
use Yii;
use yii\db\Exception;
use yii\helpers\VarDumper;

class SubscribeService
{

    /**
     * @throws Exception
     */
    public function subscribeToAuthor(string $authorName, string $phone): bool
    {
        if (empty(trim($authorName)) || empty(trim($phone))) {
            return false;
        }

        $author = Author::findByInstance(Author::fromString($authorName));
        if (empty($author)) {
            return false;
        }

        $subscriber = new Subscriber();
        $subscriber->author_id = $author->id;
        $subscriber->phone = $phone;

        return $subscriber->validate() && $subscriber->save();
    }

    /**
     * @throws \Exception
     */
    public function sendNewBookNotifications(Book $book): void
    {
        foreach ($book->authors as $author) {
            $subscribers = $this->getAuthorSubscribers($author);
            $notifyText = "DemoBook сообщает: У писателя {$author->getFullName()} вышла новая книга \"{$book->title}\"";
            Yii::$app->notificator->send(NotifySenderTypesEnum::SmsPilot, $subscribers, $notifyText);
        }
    }

    /**
     * @param Author $author
     * @return Subscriber[]
     */
    protected function getAuthorSubscribers(Author $author): array
    {
        // в рамках тестового примера вытаскиваем данные одним пакетом,
        // но вообще настраивается порционная выдача, дабы не распухала память

        return Subscriber::find()
        ->where(['author_id' => $author->id])
        ->all();
    }
}