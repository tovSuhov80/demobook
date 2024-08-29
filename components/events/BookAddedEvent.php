<?php

namespace app\components\events;

use app\models\Book;
use yii\base\Event;

class BookAddedEvent extends Event
{
    public Book $book;

    public function __construct(Book $book)
    {
        $this->book = $book;
    }
}
