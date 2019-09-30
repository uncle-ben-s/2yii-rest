<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%clients}}`.
 */
class m190918_212036_create_client_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%client}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull()->defaultValue('0'),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),

            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull()->defaultValue(time()),
            'updated_at' => $this->integer()->notNull()->defaultValue(time()),
        ]);

        $data = [
            ['first', Yii::$app->security->generatePasswordHash('000000'), '1@i.ua'],
            ['second', Yii::$app->security->generatePasswordHash('010101'), '2@i.ua'],
            ['third', Yii::$app->security->generatePasswordHash('100110'), '3@i.ua'],
        ];

        $this->batchInsert('{{%client}}', ['username', 'password_hash', 'email'], $data);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%client}}');
    }
}
