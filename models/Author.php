<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "authors".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $middle_name
 *
 * @property BookAuthor[] $bookAuthors
 * @property Book[] $books
 */
class Author extends \yii\db\ActiveRecord
{
    public int $booksCount = 0;

    public static function tableName()
    {
        return '{{%authors}}';
    }

    public function rules()
    {
        return [
            [['first_name', 'last_name'], 'required'],
            [['first_name', 'last_name', 'middle_name'], 'string', 'max' => 64],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'middle_name' => 'Middle Name',
        ];
    }


    public function getFullName(): string
    {
        return empty($this->middle_name) ? sprintf('%s %s', $this->first_name, $this->last_name)
            : sprintf('%s %s %s', $this->first_name, $this->middle_name, $this->last_name);
    }

    public static function fromString(string $authorString): ?Author
    {
        if (empty(trim($authorString))) {
            return null;
        }

        $author = new self();

        $nameParts = explode(' ', trim($authorString));
        $author->first_name = $nameParts[0];
        if (count($nameParts) > 2) {
            $author->last_name = $nameParts[2];
            $author->middle_name = $nameParts[1];
        } else {
            $author->last_name = $nameParts[1] ?? '';
        }

        return $author;
    }

    public static function findByInstance(?self $author): ?self
    {
        if (null === $author) {
            return null;
        }

        /** @var self|null $result */
        $result = self::find()->where([
            'first_name' => $author->first_name,
            'last_name' => $author->last_name,
            'middle_name' => $author->middle_name,
        ])->one();
        return $result;
    }

    public function getBookAuthors(): ActiveQuery
    {
        return $this->hasMany(BookAuthor::class, ['author_id' => 'id']);
    }

    public function getBooks(): ActiveQuery
    {
        return $this->hasMany(Book::class, ['id' => 'book_id'])
            ->viaTable('book_authors', ['author_id' => 'id']);
    }
}
