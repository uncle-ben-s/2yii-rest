<?php
namespace api\services;


use api\base\RestClientException;
use common\models\Order;
use yii\web\ServerErrorHttpException;

class OrderManager
{
    const TYPE_NOT_FOUND = 'OrderNotFound';
    const TYPE_CLOSED = 'OrderClosed';
    const TYPE_BELONGS_TO_ANOTHER_CLIENT = 'OrderBelongsToAnotherClient';
    const TYPE_INVALID_DATA = 'OrderDataValidationFailed';
    const TYPE_NOT_TICKETS = 'OrderWithoutTickets';

    private static $instance = [];

    public static function getInstance(int $id): OrderManager
    {
        if (!array_key_exists($id, static::$instance) || static::$instance[$id] === null) {
            static::$instance[$id] = new static(static::getById($id));
        }

        return static::$instance[$id];
    }

    public static function getCreatedInstance(int $clientId): OrderManager
    {
        $model = new Order();
        $model->client_id = $clientId;
        $model->status = Order::STATUS_OPEN;

        if($model->save()){
            static::$instance[$model->id] = new static($model);
//        }elseif($model->hasErrors()){
//            throw new RestClientExtendedException(OrderManager::TYPE_INVALID_DATA, $model->getErrors());
        }else{
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

        return static::$instance[$model->id];
    }

    public static function getInstanceByClientId(int $id): ?OrderManager
    {
        $model = Order::findOne([
            'client_id' => $id,
            'status'    => Order::STATUS_OPEN
        ]);

        if(!is_null($model)){
//            if(!array_key_exists($model->id, static::$instance)){
                static::$instance[$model->id] = new static($model);
//            }

            return static::$instance[$model->id];
        }

        return null;
    }

    private static function getById(int $id): ?Order
    {
        $model = Order::findOne(['id' => $id]);

        static::checkOrderIsFound($model);

        static::validateOrder($model);

        static::orderBelongsToAntherClient($model, \Yii::$app->getUser()->id);

        return $model;
    }

    public static function checkOrderIsFound($model){
        if(is_null($model)){
            throw new RestClientException( OrderManager::TYPE_NOT_FOUND);
        }
    }

    public static function validateOrder(Order $model){

        if($model->status === Order::STATUS_CLOSE){
            throw new RestClientException( OrderManager::TYPE_CLOSED);
        }
    }

    public static function orderBelongsToAntherClient(Order $model, int $currentClientId){
        if($model->isBelongsToAnotherClient($currentClientId)){
            throw new RestClientException( OrderManager::TYPE_BELONGS_TO_ANOTHER_CLIENT);
        }
    }



    private function __construct(Order $model){
        $this->model = $model;
    }

    private function __clone(){}

    private function __wakeup(){}

    private $model;

    public function getModel(): ?Order
    {
        return $this->model;
    }

    public function getTicketsCount(){
        return (is_null($this->model->orderTickets))? 0 : count($this->model->orderTickets);
    }

    public function checkIsTicketsIsset(){
        if($this->getTicketsCount() === 0){
            throw new RestClientException( OrderManager::TYPE_NOT_TICKETS);
        }
    }

    public function getAmount(){
            $amount = 0;

        if(is_null($this->model->orderTickets)){
            return $amount;
        }

        foreach($this->model->orderTickets as $orderTicket){
            $amount += $orderTicket->ticket->amount;
        }

        return $amount;
    }

    public function closeOrder(string $ticketStatus){
        $this->model->status = Order::STATUS_CLOSE;
        if(!is_null($this->model->orderTickets) && $this->model->save()){
            foreach($this->model->orderTickets as $orderTicket){
                $orderTicket->ticket->status = $ticketStatus;
                $orderTicket->ticket->save();
            }
        }
    }
}