<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%book_authors}}`.
 */
class m240827_154554_create_book_authors_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%book_authors}}', [
            'book_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
        ],'CHARACTER SET utf8');

        $this->createIndex('idx-book_authors-book_id', '{{%book_authors}}', ['book_id']);
        $this->createIndex('idx-book_authors-author_id', '{{%book_authors}}', ['author_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%book_authors}}');
    }
}
