<?php
namespace api\services;

use api\base\RestClientException;
use common\models\Ticket;
use Yii;
use yii\web\ServerErrorHttpException;

class TicketManager
{
    const TYPE_NOT_FOUND = 'TicketNotFound';
    const TYPE_NOT_AVAILABLE = 'TicketNotAvailable';
    const TYPE_RESERVED = 'TicketReserved';
//    const TYPE_CLOSED = 'TicketClosed';
    const TYPE_EXPIRED = 'TicketExpired';
    const TYPE_BLOCKED_BY_ANOTHER_CLIENT = 'TicketBlockedByAnotherClient';
//    const TYPE_INVALID_DATA = 'TicketDataValidationFailed';

    private static $instance = [];

    public static function getInstance(int $id): TicketManager
    {
        if (!array_key_exists($id, static::$instance) || static::$instance[$id] === null) {
            static::$instance[$id] = new static(static::getById($id));
        }

        return static::$instance[$id];
    }

    private static function getById(int $id): ?Ticket
    {
        $model = Ticket::findOne($id);

        static::checkTicketIsFound($model);

        static::validateTicket($model);

        static::ticketIsBlockedByAnotherClient($model, Yii::$app->getUser()->id);

        return $model;
    }

    public static function checkTicketIsFound($model){
        if(is_null($model)){
            throw new RestClientException( TicketManager::TYPE_NOT_FOUND);
        }
    }

    public static function validateTicket(Ticket $model){

        if($model->status === Ticket::STATUS_CLOSE){
            throw new RestClientException( TicketManager::TYPE_NOT_AVAILABLE);
        }

        if($model->status === Ticket::STATUS_RESERVE && ($model->expired_at - 1800 ) > time()){ //1800 seconds -> half-hour
            throw new RestClientException( TicketManager::TYPE_RESERVED);
        }

        if($model->expired_at < time()){
            throw new RestClientException( TicketManager::TYPE_EXPIRED);
        }
    }

    public static function ticketIsBlockedByAnotherClient(Ticket $model, int $currentClientId){
        if($model->block_expired_at > time() && $model->isBlockedByAnotherClient($currentClientId)){
            throw new RestClientException( TicketManager::TYPE_BLOCKED_BY_ANOTHER_CLIENT);
        }
    }

    public static function getAll(): array
    {
        return Ticket::find()
            ->joinWith('order')
            ->asArray()
            ->all();
    }



    private $model;

    private function __construct(Ticket $model){
        $this->model = $model;
    }

    private function __clone(){}

    private function __wakeup(){}

    public function getModel(): ?Ticket
    {
        return $this->model;
    }

    public function isBlocked(){
        return ($this->model->block_expired_at > time());
    }

    public function setExpiredAt(int $timeStamp){
        $this->model->block_expired_at = $timeStamp + Ticket::BLOCKED_TIME;

        if (!$this->model->save() && !$this->model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
    }

    public function unsetExpiredAt(){
        $this->setExpiredAt(time() - Ticket::BLOCKED_TIME);
    }
}