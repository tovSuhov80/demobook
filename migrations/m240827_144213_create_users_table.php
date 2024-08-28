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
            'password_hash' => $this->string(64)->notNull(),
            'auth_key' => $this->string(64)->notNull(),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP()')->notNull(),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP()')->notNull(),
        ], 'CHARACTER SET utf8');

        //сразу создаем пользователя ID=1 username=admin password=demo123
        $this->execute('INSERT INTO {{%users}} (id, username, password_hash, auth_key) VALUES 
(1, \'admin\',\'$2y$13$NWQr0mJgGrZGcIodqRc.7Ow0focUQQkO5Lj1QRY1uCaENODs7K43C\', \'5dtJ5hlHHv9aBF-1NOXKVAZxgcOZwCYmd988A4ogDQYd7nYZSaWLWorO3_B5W3bP\');');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users}}');
    }
}
