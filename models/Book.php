<?php

namespace app\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**

 *
 * @property int $id
 * @property int $user_id
 * @property string $isbn
 * @property string $title
 * @property string $description
 * @property int $release_year
 *
 * @property BookAuthor[] $bookAuthors
 * @property Author[] $authors
 */
class Book extends \yii\db\ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%books}}';
    }

    public function rules(): array
    {
        return [
            [['title', 'isbn', 'release_year'], 'required'],
            [['title', 'description'], 'string'],
            [['release_year'], 'integer'],
            [['isbn'], 'string', 'max' => 18],
            [['photo_url'], 'url'],
        ];
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
            ]
        ];
    }


    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'isbn' => 'ISBN',
            'title' => Yii::t('app', 'Название'),
            'description' => Yii::t('app', 'Описание'),
            'release_year' => Yii::t('app', 'Год'),
            'photo_url' => Yii::t('app', 'Ссылка на изображение обложки')
        ];
    }

    public function getBookAuthors(): ActiveQuery
    {
        return $this->hasMany(BookAuthor::class, ['book_id' => 'id']);
    }

    /**
     * @throws InvalidConfigException
     */
    public function getAuthors(): ActiveQuery
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable('{{%book_authors}}', ['book_id' => 'id']);
    }

    public function getAuthorsAsString(): string
    {
        $result = [];
        foreach ($this->authors as $author) {
            $result[] = $author->getFullName();
        }
        return implode(', ', $result);
    }
}