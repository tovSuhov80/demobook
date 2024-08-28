<?php

namespace app\models;

use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * This is the model class for table "books".
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
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'books';
    }

    public function rules(): array
    {
        return [
            [['user_id', 'release_year'], 'integer'],
            [['title', 'description'], 'string'],
            [['isbn'], 'string', 'max' => 18],
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
            'title' => 'Title',
            'description' => 'Description',
            'release_year' => 'Release Year',
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
            ->viaTable('book_authors', ['book_id' => 'id']);
    }
}