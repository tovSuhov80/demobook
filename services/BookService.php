<?php

namespace app\services;

use app\models\Author;
use app\models\Book;
use app\models\forms\BookForm;
use yii\db\ActiveQuery;

class BookService
{
    public function saveBook(BookForm $bookForm, ?int $bookId, int $userId): bool
    {
        $book = new Book();
        $book->setAttributes($bookForm->getAttributes());
        $book->setIsNewRecord(null === $bookId);
        $book->id = $bookId;
        $book->user_id = $userId;

        if ($book->save()) {
            foreach ($bookForm->author_names as $authorName) {
                $formAuthor = Author::fromString($authorName);
                if (empty($formAuthor)) {
                    continue;
                }

                $author = Author::find()->where([
                    'first_name' => $formAuthor->first_name,
                    'last_name' => $formAuthor->last_name,
                    'middle_name' => $formAuthor->middle_name,
                ])->one();

                if (!$author) {
                    $formAuthor->save();
                }

                // Сохранение связи книги и автора
                $bookAuthor = new BookAuthors();
                $bookAuthor->book_id = $this->_book->id;
                $bookAuthor->author_id = $author->id;
                $bookAuthor->save();
            }
            return true;
        }

    }

    public function getReleasedYears(?int $exludeYear = null): array
    {
        $bookQuery = Book::find()
            ->select(['release_year'])
            ->distinct()
            ->orderBy(['release_year' => SORT_DESC]);
        if (null !== $exludeYear) {
            $bookQuery->where(['!=', 'release_year', $exludeYear]);
        }

        return $bookQuery->column();
    }

    public function getBooksByYear(int $year): ActiveQuery
    {
        return Book::find()
            ->with('authors')
            ->where(['release_year' => $year])
            ->orderBy(['title' => SORT_ASC]);
    }

    public function getBooksByUserId(int $userId): ActiveQuery
    {
        return Book::find()
            ->with('authors')
            ->where(['user_id' => $userId])
            ->orderBy(['title' => SORT_ASC]);
    }

}