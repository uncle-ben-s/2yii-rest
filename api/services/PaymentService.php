<?php
namespace api\services;


use yii\web\HttpException;

class PaymentService
{
    const TYPE_BUY = 'buy';
    const TYPE_RESERVE = 'reserve';

    private static $instance;

    public static function getInstance(): PaymentService
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    private function __construct(){}

    private function __clone(){}

    private function __wakeup(){}


    private $isPaymentSuccess = false;

    public function paymentSuccessReset(){
        $this->isPaymentSuccess = false;
    }

    public function pay(OrderManager $om, string $status){
        /**/

        $this->isPaymentSuccess = true;

        return $this;
    }

    public function isPaymentSuccess(){
        return $this->isPaymentSuccess;
    }

    public function afterPayment(OrderManager $om, string $ticketStatus){
        if($this->isPaymentSuccess()){
            $om->closeOrder($ticketStatus);
            $this->isPaymentSuccess = false;
        }else{
            throw new HttpException( 402, 'PaymentFailed');
        }
    }
}