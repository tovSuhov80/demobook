<?php

namespace app\services;

use app\models\Author;

class AuthorService
{

    /**
     * @param int $year
     * @param int $limit
     * @return Author[]
     */
    public function getTopAuthorsByYear(int $year, int $limit = 10): array
    {
        return Author::find()
            ->alias('author')
            ->select(['author.*', 'COUNT(ba.book_id) as booksCount'])
            ->innerJoin('{{%book_authors}} ba', 'ba.author_id = author.id')
            ->innerJoin('{{%books}} b', 'b.id = ba.book_id AND b.release_year = :year', [':year' => $year])
            ->groupBy('author.id')
            ->orderBy(['booksCount' => SORT_DESC, 'author.first_name' => SORT_ASC])
            ->limit($limit)
            ->all();
    }

}