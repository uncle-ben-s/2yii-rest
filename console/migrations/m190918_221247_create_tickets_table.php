<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tickets}}`.
 */
class m190918_221247_create_tickets_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ticket}}', [
            'id' => $this->primaryKey(),
            'column' => $this->integer()->notNull(),
            'row' => $this->integer()->notNull(),
            'amount' => $this->smallInteger()->notNull(),
            'status' => "enum('open', 'close', 'reserve') NOT NULL DEFAULT 'close'",
            'block_expired_at' => $this->integer(),
            'expired_at' => $this->integer(),
        ]);

        $placesRows = [
            ['row' => 1, 'cols' => 12],
            ['row' => 2, 'cols' => 14],
            ['row' => 3, 'cols' => 15],
        ];

        $data = [];

        $expiredAt = time() + (3600*24*7);

        foreach($placesRows as $row){
            for($i=0; $i < $row['cols']; $i++ ){
                $data[] = [($i+1), $row['row'], (rand(1,2) % 2 == 0)? 'open':'close', 55, $expiredAt];
            }
        }

        if(count($data)){
            $this->batchInsert('{{%ticket}}', ['column', 'row', 'status', 'amount', 'expired_at'], $data);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ticket}}');
    }
}
