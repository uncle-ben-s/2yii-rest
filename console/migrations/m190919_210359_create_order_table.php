<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order}}`.
 */
class m190919_210359_create_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%order}}', [
            'id' => $this->primaryKey(),
            'client_id' => $this->integer()->notNull(),
            'status' => "enum('open', 'close') NOT NULL DEFAULT 'open'",
        ]);

        $this->createIndex(
            'idx-order-client_id',
            'order',
            'client_id'
        );

        $this->addForeignKey(
            'fk-order-client_id',
            'order',
            'client_id',
            'client',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-order-client_id',
            'order'
        );

        $this->dropIndex(
            'idx-order-client_id',
            'order'
        );

        $this->dropTable('{{%order}}');
    }
}
