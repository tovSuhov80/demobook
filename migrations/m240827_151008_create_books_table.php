<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%books}}`.
 */
class m240827_151008_create_books_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('{{%books}}', [
            'id' => $this->primaryKey(),
            'isbn' => $this->string(13)->notNull()->unique(),
            'title' => $this->text()->notNull(),
            'description' => $this->text()->null(),
            'release_year' => $this->integer()-> notNull(),
            'photoUrl' => $this->string(1024)->null(),
        ],'CHARACTER SET utf8');

        $this->createIndex('idx-books-release_year', '{{%books}}', 'release_year');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable('{{%books}}');
    }
}
