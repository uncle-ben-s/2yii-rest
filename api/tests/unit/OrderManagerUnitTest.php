<?php namespace api\tests;

use api\base\RestClientException;
use api\services\OrderManager;
use common\models\Order;
use common\models\OrderTicket;
use common\models\Ticket;

class OrderManagerUnitTest extends \Codeception\Test\Unit
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
        $this->expectException(RestClientException::class);
        $this->expectExceptionMessage('OrderNotFound');

        OrderManager::checkOrderIsFound(null);
    }

    public function testOrderClosed()
    {

        $this->expectException(RestClientException::class);
        $this->expectExceptionMessage('OrderClosed');

        OrderManager::validateOrder($this->make(Order::class, [
            'attributes' => function() { return ['id', 'status']; },
            'status' => 'close'
        ]));
    }

    public function testOrderBlockedByAnotherClient()
    {
        $this->expectException(RestClientException::class);
        $this->expectExceptionMessage('OrderBelongsToAnotherClient');

        OrderManager::orderBelongsToAntherClient($this->make(Order::class, [
            'attributes' => function() { return ['id', 'status', 'client_id']; },
            'status' => 'open',
            'client_id' => 1,
        ]), 2);
    }

    public function testCheckIsTicketsIssetFailed()
    {
        $this->expectException(RestClientException::class);
        $this->expectExceptionMessage('OrderWithoutTickets');

        $om = $this->make(OrderManager::class, [
            'model' => $this->make(Order::class, [
                'attributes' => function() { return ['id', 'orderTickets']; },
            ])
        ]);

        $om->checkIsTicketsIsset();
    }

    public function testGetAmountSuccess(){
        $om = $this->make(OrderManager::class, [
            'model' => $this->make(Order::class, [
                'save' => function() { return true; },
                'attributes' => function() { return ['id', 'client_id', 'status', 'orderTickets']; },
                'orderTickets' => [
                    $this->make(OrderTicket::class, [
                        'save' => function() { return true; },
                        'attributes' => function() { return ['id', 'order_id', 'ticket_id', 'ticket']; },
                        'ticket' => $this->make(Ticket::class, [
                            'attributes' => function() { return ['id', 'amount']; },
                            'amount' => 55
                        ])
                    ]),
                    $this->make(OrderTicket::class, [
                        'save' => function() { return true; },
                        'attributes' => function() { return ['id', 'order_id', 'ticket_id', 'ticket']; },
                        'ticket' => $this->make(Ticket::class, [
                            'attributes' => function() { return ['id', 'amount']; },
                            'amount' => 56
                        ])
                    ]),
                ]
            ])
        ]);

        $this->assertEquals($om->getAmount(), 111);
    }

    public function testGetAmountWithoutTickets(){
        $om = $this->make(OrderManager::class, [
            'model' => $this->make(Order::class, [
                'save' => function() { return true; },
                'attributes' => function() { return ['id', 'client_id', 'status', 'orderTickets']; },
            ])
        ]);

        $this->assertEquals($om->getAmount(), 0);
    }

    public function testCloseOrder(){
        $om = $this->make(OrderManager::class, [
            'model' => $this->make(Order::class, [
                'save' => function() { return true; },
                'attributes' => function() { return ['id', 'client_id', 'status', 'orderTickets']; },
                'status' => 'open',
                'orderTickets' => [
                    $this->make(OrderTicket::class, [
                        'save' => function() { return true; },
                        'attributes' => function() { return ['id', 'order_id', 'ticket_id', 'ticket']; },
                        'ticket' => $this->make(Ticket::class, [
                            'save' => function() { return true; },
                            'attributes' => function() { return ['id', 'status']; },
                            'status' => 'open',
                        ])
                    ]),
                    $this->make(OrderTicket::class, [
                        'save' => function() { return true; },
                        'attributes' => function() { return ['id', 'order_id', 'ticket_id', 'ticket']; },
                        'ticket' => $this->make(Ticket::class, [
                            'save' => function() { return true; },
                            'attributes' => function() { return ['id', 'status']; },
                            'status' => 'open',
                        ])
                    ]),
                ]
            ])
        ]);

        $this->assertEquals($om->getModel()->status, 'open');

        foreach($om->getModel()->orderTickets as $item){
            $this->assertEquals($item->ticket->status, 'open');
        }

        $om->closeOrder(Ticket::STATUS_RESERVE);

        $this->assertEquals($om->getModel()->status, 'close');

        foreach($om->getModel()->orderTickets as $item){
            $this->assertEquals($item->ticket->status, 'reserve');
        }
    }

}