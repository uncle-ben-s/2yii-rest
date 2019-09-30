<?php
namespace api\services;


use api\base\RestClientExtendedException;
use api\base\RestClientException;
use common\models\OrderTicket;
use yii\web\ServerErrorHttpException;

class OrderTicketManager
{
    const TYPE_NOT_FOUND = 'OrderTicketNotFound';
    const TYPE_BELONGS_TO_ANOTHER_CLIENT = 'OrderTicketBelongsToAnotherClient';
    const TYPE_INVALID_DATA = 'OrderTicketDataValidationFailed';

    private static $instance = [];

    public static function getInstance(int $id): OrderTicketManager
    {
        if (!array_key_exists($id, static::$instance) || static::$instance[$id] === null) {
            static::$instance[$id] = new static(static::getById($id));
        }

        return static::$instance[$id];
    }

    public static function getCreatedInstance(int $orderId, int $ticketId): OrderTicketManager
    {
        if(($orderTicketManagerInstance = static::getInstanceByParams(['order_id' => $orderId, 'ticket_id' => $ticketId]))){
            return $orderTicketManagerInstance;
        }

        $model = new OrderTicket();
        $model->order_id = $orderId;
        $model->ticket_id = $ticketId;

        if($model->save()){
            static::$instance[$model->id] = new static($model);
        }elseif($model->hasErrors()){
            throw new RestClientExtendedException(OrderTicketManager::TYPE_INVALID_DATA, $model->getErrors());
        }else{
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

        return static::$instance[$model->id];
    }

    public static function getInstanceByParams(Array $params): ?OrderTicketManager
    {
        $model = static::findByParams($params);

        if(!is_null($model)){

            if($model->order->client_id !== \Yii::$app->getUser()->id){
                throw new RestClientException( OrderTicketManager::TYPE_BELONGS_TO_ANOTHER_CLIENT);
            }

            static::$instance[$model->id] = new static($model);
            return static::$instance[$model->id];
        }

        return null;
    }

    private static function findByParams(Array $params): ?OrderTicket{
        return OrderTicket::findOne($params);
    }

    private static function getById(int $id): ?OrderTicket
    {
        $model = OrderTicket::findOne(['id' => $id]);

        static::checkOrderTicketIsFound($model);

        static::validateOrderTicket($model);

        static::orderTicketIsBelongsToAnotherClient($model, \Yii::$app->getUser()->id);

        return $model;
    }

    public static function checkOrderTicketIsFound($model){
        if(is_null($model)){
            throw new RestClientException( OrderTicketManager::TYPE_NOT_FOUND);
        }
    }

    public static function validateOrderTicket(OrderTicket $model){
    }

    public static function orderTicketIsBelongsToAnotherClient(OrderTicket $model, int $currentClientId){
        if($model->isBelongsToAnotherClient($currentClientId)){
            throw new RestClientException( OrderTicketManager::TYPE_BELONGS_TO_ANOTHER_CLIENT);
        }
    }

    public static function validateModelData($createdModelData){
        $model = new OrderTicket();
        $model->load($createdModelData, '');
        if(!$model->validate()){
            throw new RestClientExtendedException(OrderTicketManager::TYPE_INVALID_DATA, $model->getErrors());
        }

        unset($model);
    }


    private $model;

    private function __construct(OrderTicket $model){
        $this->model = $model;
    }

    private function __clone(){}

    private function __wakeup(){}

    public function getModel(): ?OrderTicket
    {
        return $this->model;
    }

}