<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users}}`.
 */
class m240827_144213_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(128)->notNull()->unique(),
            'password_hash' => $this->string(60)->notNull(),
        ], 'CHARACTER SET utf8');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users}}');
    }
}
