<?php

namespace app\models\forms;


use app\models\Book;
use Yii;
use yii\helpers\ArrayHelper;

class BookForm extends Book
{
    public string $author_names = ''; // Список ФИО авторов

    public function rules(): array
    {
        return ArrayHelper::merge(
            parent::rules(), [
                [['author_names'], 'required'],
                [['author_names'], 'string'],
            ]
        );
    }

    public function attributeLabels(): array
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'author_names' => Yii::t('app', 'Авторы'),
        ]);
    }

}
