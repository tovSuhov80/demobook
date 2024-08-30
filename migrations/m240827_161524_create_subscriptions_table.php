<?php

use yii\db\Migration;

class m240827_161524_create_subscriptions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%subscriptions}}', [
            'id' => $this->primaryKey(),
            'phone' => $this->string(13)->notNull(),
            'author_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-subscriptions-author_id',
            '{{%subscriptions}}',
            'author_id',
            '{{%authors}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex('idx-subscriptions-author_id-phone', '{{%subscriptions}}', ['author_id', 'phone'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%subscriptions}}');
    }
}
