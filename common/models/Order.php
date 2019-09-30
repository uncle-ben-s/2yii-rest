<?php

namespace common\models;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property int $client_id
 * @property string $status
 *
 * @property Client $client
 * @property OrderTicket[] $orderTickets
 */
class Order extends \yii\db\ActiveRecord
{
    const STATUS_OPEN = 'open';
    const STATUS_CLOSE = 'close';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id'], 'required'],
            [['client_id'], 'integer'],
            [['status'], 'string'],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::class, 'targetAttribute' => ['client_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_id' => 'Client ID',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::class, ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderTickets()
    {
        return $this->hasMany(OrderTicket::class, ['order_id' => 'id']);
    }

    public function getTickets()
    {
        return $this->hasMany(Ticket::class, ['id' => 'ticket_id'])
            ->via('orderTickets');
    }

    public function getAmount()
    {
        return $this->hasMany(Ticket::class, ['id' => 'ticket_id'])
            ->via('orderTickets')->sum('amount');
    }

    public function isBelongsToAnotherClient($id){
        return ($this->client_id !== $id);
    }
}
