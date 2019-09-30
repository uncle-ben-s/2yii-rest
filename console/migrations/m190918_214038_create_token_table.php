<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%token}}`.
 */
class m190918_214038_create_token_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(){
        $this->createTable('{{%token}}', [
            'id'         => $this->primaryKey(),
            'client_id'    => $this->integer()->notNull(),
            'token'      => $this->string()->notNull()->unique(),
            'expired_at' => $this->integer()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createIndex(
            'idx-token-client_id',
            'token',
            'client_id'
        );

        $this->addForeignKey(
            'fk-token-client_id',
            'token',
            'client_id',
            'client',
            'id',
            'CASCADE',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(){

        $this->dropForeignKey(
            'fk-token-client_id',
            'token'
        );

        $this->dropIndex(
            'idx-token-client_id',
            'token'
        );

        $this->dropTable('{{%token}}');
    }
}
