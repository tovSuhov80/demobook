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
            'user_id' => $this->integer()->notNull(),
            'isbn' => $this->string(18)->notNull()->unique(),
            'title' => $this->text()->notNull(),
            'description' => $this->text()->null(),
            'release_year' => $this->integer()-> notNull(),
            'photo_url' => $this->string(1024)->null(),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP()')->notNull(),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP()')->notNull(),

        ],'CHARACTER SET utf8');

        $this->createIndex('idx-books-user_id', '{{%books}}', 'user_id');
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
