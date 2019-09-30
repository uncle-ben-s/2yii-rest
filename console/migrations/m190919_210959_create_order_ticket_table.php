<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order_ticket}}`.
 */
class m190919_210959_create_order_ticket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%order_ticket}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'ticket_id' => $this->integer()->notNull()->unique(),
        ]);

        $this->createIndex(
            'idx-order_ticket-ticket_id',
            'order_ticket',
            'ticket_id'
        );

        $this->addForeignKey(
            'fk-order_ticket-ticket_id',
            'order_ticket',
            'ticket_id',
            'ticket',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-order_ticket-order_id',
            'order_ticket',
            'order_id'
        );

        $this->addForeignKey(
            'fk-order_ticket-order_id',
            'order_ticket',
            'order_id',
            'order',
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
            'fk-order_ticket-ticket_id',
            'order_ticket'
        );

        $this->dropIndex(
            'idx-order_ticket-ticket_id',
            'order_ticket'
        );

        $this->dropForeignKey(
            'fk-order_ticket-order_id',
            'order_ticket'
        );

        $this->dropIndex(
            'idx-order_ticket-order_id',
            'order_ticket'
        );

        $this->dropTable('{{%order_ticket}}');
    }
}
