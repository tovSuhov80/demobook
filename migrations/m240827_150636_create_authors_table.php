<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%authors}}`.
 */
class m240827_150636_create_authors_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%authors}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(128)->notNull(),
        ],'CHARACTER SET utf8');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%authors}}');
    }
}
