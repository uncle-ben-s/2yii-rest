<?php

namespace common\models;

use admin\behaviors\DateToTimeBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ticket".
 *
 * @property int $id
 * @property int $column
 * @property int $row
 * @property int $amount
 * @property string $status
 * @property int $block_expired_at
 * @property int $expired_at
 *
 * @property OrderTicket[] $orderTickets
 */
class Ticket extends ActiveRecord
{
    const STATUS_OPEN = 'open';
    const STATUS_CLOSE = 'close';
    const STATUS_RESERVE = 'reserve';

    const BLOCKED_TIME = (5*60); //5 min
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ticket';
    }

    public $expired_at_formatted;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['column', 'row', 'amount'], 'required'],
            [['column', 'row', 'expired_at'], 'integer'],
            ['expired_at_formatted', 'datetime', 'format' => 'php:M d, Y H:i:s'],
            [['amount'], 'integer'],
            [['status'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'column' => 'Column',
            'row' => 'Row',
            'amount' => 'Amount',
            'status' => 'Status',
            'block_expired_at' => 'Block Expired At',
            'expired_at' => 'Expired At',
            'expired_at_formatted' => 'Expired At'
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => DateToTimeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_VALIDATE => 'expired_at_formatted',
                    ActiveRecord::EVENT_AFTER_FIND => 'expired_at_formatted',
                ],
                'timeAttribute' => 'expired_at'
            ]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderTicket()
    {
        return $this->hasOne(OrderTicket::class, ['ticket_id' => 'id']);
    }

    public function getOrder()
    {
        return $this->hasOne(Order::class, ['id' => 'order_id'])
            ->via('orderTicket');
    }

    public function isBlockedByAnotherClient($id){
        return ($this->orderTicket->order->client_id !== $id);

    }
}
