<?php

namespace app\services;

use app\components\events\BookAddedEvent;
use app\models\Author;
use app\models\Book;
use app\models\forms\BookForm;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\helpers\VarDumper;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class BookService
{
    public function saveBook(BookForm $bookForm, ?int $bookId, int $userId): bool
    {
        $book = null === $bookId ? new Book() : Book::findOne($bookId);

        $book->setAttributes($bookForm->getAttributes());
        $book->user_id = $userId;

        $newAuthors = [];
        $oldAuthors = [];

        $transaction = Yii::$app->db->beginTransaction();
        try {

            if (null !== $bookId && $oldBook = Book::findOne($bookId)) {
                $oldAuthors = $oldBook->getAuthors()->indexBy('id')->all();
            }

            if ($book->save()) {

                foreach (explode(',', $bookForm->author_names) as $authorName) {
                    $formAuthor = Author::fromString($authorName);
                    if (null === $formAuthor) {
                        continue;
                    }

                    $author = Author::findByInstance($formAuthor);

                    if (null === $author && $formAuthor->save()) {
                        $author = $formAuthor;
                    }

                    if (!isset($oldAuthors[$author->id])) { //это новый автор
                        $newAuthors[] = $author;
                    } else {
                        //удаляем из массива старой версии текущего автора
                        unset($oldAuthors[$author->id]);
                    }
                }

                // Сохраняем связи с новыми авторами
                foreach ($newAuthors as $newAuthor) {
                    $book->link('authors', $newAuthor);
                }

                //в $oldAuthors остались авторы, отсутствующие в новой версии исправления, удаляем эти связи
                foreach ($oldAuthors as $oldAuthor) {
                    $book->unlink('authors', $oldAuthor, true);
                }

                if ($book->getAuthors()->count() === 0) {
                    //книга по итогу не может остаться без авторов
                    throw new Exception("Отсутствуют авторы для книги Book[{$bookId}]");
                }

                $transaction->commit();
            } else {
                throw new Exception("Ошибка сохранения Book[{$bookId}]");
            }
        } catch (\Throwable $exception) {
            $transaction->rollBack();
            Yii::error($exception->getMessage());
            return false;
        }

        //создаем событие "Была добавлена книга"
        if (null === $bookId) {
            Yii::$app->eventDispatcher->dispatch(new BookAddedEvent($book));
        }
        return true;
    }

    /**
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function deleteBook(Book $book): bool
    {
        return $book->delete();
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
            ->orderBy(['updated_at' => SORT_DESC]);
    }

    /**
     * @throws ForbiddenHttpException
     */
    public function assertBookAccess(Book $book): void
    {
        if ($book->user_id !== Yii::$app->user->id) {
            throw new ForbiddenHttpException("Нет доступа к данной книге");
        }
    }

    /**
     * @throws NotFoundHttpException
     */
    public function getBookOrFail(int $bookId): Book
    {
        if (null === $book = Book::findOne($bookId)) {
            throw new NotFoundHttpException("Такая книга не найдена");
        }

        return $book;
    }


}