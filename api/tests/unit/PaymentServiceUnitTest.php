<?php namespace api\tests;

use api\services\OrderManager;
use api\services\PaymentService;
use common\models\Order;
use yii\web\HttpException;

class PaymentServiceUnitTest extends \Codeception\Test\Unit
{
    /**
     * @var \api\tests\UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testSuccessFlagReset()
    {
        $ps = PaymentService::getInstance();

        $this->assertFalse($ps->isPaymentSuccess());

        $ps->pay($this->make(OrderManager::class), 'payment status');

        $this->assertTrue($ps->isPaymentSuccess());

        $ps->paymentSuccessReset();

        $this->assertFalse($ps->isPaymentSuccess());
    }

    public function testSuccessFlag()
    {
        $ps = PaymentService::getInstance();

        $this->assertFalse($ps->isPaymentSuccess());

        $ps->pay($this->make(OrderManager::class), 'payment status');

        $this->assertTrue($ps->isPaymentSuccess());

        $ps->paymentSuccessReset();
    }

    public function testSuccessFinishPay()
    {
        $ps = PaymentService::getInstance();

        $om = $this->make(OrderManager::class, [
            'model' => $this->make(Order::class, [
                'save' => function() { return true; },
                'attributes' => function() { return [ 'status', 'client_id', 'orderTickets' ]; },
                'client_id' => 1,
                'status' => 'open'
            ])
        ]);

        $this->assertEquals('open', $om->getModel()->status);

        $ps->pay($om, 'payment status')->afterPayment($om, 'ticket status');

        $this->assertFalse($ps->isPaymentSuccess());
        $this->assertEquals('close', $om->getModel()->status);
    }

    public function testFailedFinishPay()
    {
        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('PaymentFailed');

        $ps = PaymentService::getInstance();

        $this->assertFalse($ps->isPaymentSuccess());

        $ps->afterPayment($this->make(OrderManager::class, [
            'model' => $this->make(Order::class, [
                'save' => function() { return true; },
                'attributes' => function() { return [ 'status', 'client_id', 'orderTickets' ]; },
                'status' => 'open'
            ])
        ]), 'ticket status');
    }
}