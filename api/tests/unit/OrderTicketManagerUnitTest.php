<?php namespace api\tests;

use api\base\RestClientException;
use api\services\OrderTicketManager;
use common\models\Order;
use common\models\OrderTicket;

class OrderTicketManagerUnitTest extends \Codeception\Test\Unit
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
    public function testNotFound()
    {
        OrderTicketManager::checkOrderTicketIsFound($this->make(OrderTicket::class));

        $this->expectException(RestClientException::class);
        $this->expectExceptionMessage('OrderTicketNotFound');

        OrderTicketManager::checkOrderTicketIsFound(null);
    }

    public function testOrderBlockedByAnotherClient()
    {
        $this->expectException(RestClientException::class);
        $this->expectExceptionMessage('OrderTicketBelongsToAnotherClient');

         $ot = $this->make(OrderTicket::class, [
            'save' => function() { return true; },
            'attributes' => function() { return ['id', 'order_id', 'ticket_id', 'order']; },
            'order' => $this->make(Order::class, [
                'attributes' => function() { return [ 'client_id', 'status' ]; },
                'status' => 'open',
                'client_id' => 1,
            ])
        ]);

        OrderTicketManager::orderTicketIsBelongsToAnotherClient($ot, 2);
    }
}