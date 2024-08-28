<?php

namespace app\models\forms;


use Yii;
use yii\base\Model;
use app\models\Book;
use app\models\Author;
use yii\helpers\ArrayHelper;

class BookForm extends Book
{
    public $author_names = []; // Список ФИО авторов

    public function rules(): array
    {
        return ArrayHelper::merge(
            parent::rules(), [
                [['author_names'], 'each', 'rule' => ['string']],
            ]
        );
    }
}
