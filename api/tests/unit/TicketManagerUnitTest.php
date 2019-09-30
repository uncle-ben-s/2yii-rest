<?php namespace api\tests;

use api\base\RestClientException;
use api\services\TicketManager;
use common\models\Ticket;

class TicketManagerUnitTest extends \Codeception\Test\Unit
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
        $this->expectExceptionMessage('TicketNotFound');

        TicketManager::checkTicketIsFound(null);
    }

    public function testTickedClosed()
    {

        $this->expectException(RestClientException::class);
        $this->expectExceptionMessage('TicketNotAvailable');

        TicketManager::validateTicket($this->make(Ticket::class, [
            'attributes' => function() { return [ 'status' ]; },
            'status' => 'close'
        ]));
    }

    public function testTickedReserved()
    {
        $this->expectException(RestClientException::class);
        $this->expectExceptionMessage('TicketReserved');

        TicketManager::validateTicket($this->make(Ticket::class, [
            'attributes' => function() { return [ 'status', 'expired_at' ]; },
            'status' => 'reserve',
            'expired_at' => time() + 3600
        ]));
    }

    public function testTickedExpired()
    {
        $this->expectException(RestClientException::class);
        $this->expectExceptionMessage('TicketExpired');

        TicketManager::validateTicket($this->make(Ticket::class, [
            'attributes' => function() { return [ 'status', 'expired_at' ]; },
            'status' => 'open',
            'expired_at' => time() - 1
        ]));
    }

    public function testTickedBlockedByAnotherClient()
    {
        $this->expectException(RestClientException::class);
        $this->expectExceptionMessage('TicketBlockedByAnotherClient');

        $tm = $this->make(Ticket::class, [
            'attributes' => function() { return [ 'status', 'expired_at', 'block_expired_at' ]; },
            'block_expired_at' => time() + 300,
            'status' => 'open',
            'expired_at' => time() + 3600,
            'isBlockedByAnotherClient' => function($id){
                $ticketClientId = 1; //via order-ticket -> order
                return ($ticketClientId !== $id);
            }
        ]);

        TicketManager::ticketIsBlockedByAnotherClient($tm, 2);
    }

    public function testIsBlockedSuccess()
    {
        $tm = $this->make(TicketManager::class, [
            'model' => $this->make(Ticket::class, [
                'attributes' => function() { return [ 'block_expired_at' ]; },
                'block_expired_at' => time() + 300
            ])
        ]);

        $this->assertTrue($tm->isBlocked());
    }

    public function testIsBlockedFailed()
    {
        $tm = $this->make(TicketManager::class, [
            'model' => $this->make(Ticket::class, [
                'attributes' => function() { return [ 'block_expired_at' ]; },
                'block_expired_at' => time() - 300
            ])
        ]);

        $this->assertFalse($tm->isBlocked());
    }

    public function testSetExpiredAt()
    {
        $timeStamp = time();
        $timeSet = $timeStamp + 250;

        $tm = $this->make(TicketManager::class, [
            'model' => $this->make(Ticket::class, [
                'save' => function() { return true; },
                'attributes' => function() { return [
                        'id', 'column','row','amount','status','block_expired_at','expired_at','expired_at_formatted'
                    ];
                },
                'block_expired_at' => $timeStamp,
            ])
        ]);

        $this->assertTrue($tm->getModel()->block_expired_at === $timeStamp);

        $tm->setExpiredAt($timeSet);

        $this->assertTrue($tm->getModel()->block_expired_at === $timeSet + Ticket::BLOCKED_TIME);
    }

    public function testUnsetExpiredAt()
    {
        $timeStamp = time();
        $blockedTime = $timeStamp + 300;

        $tm = $this->make(TicketManager::class, [
            'model' => $this->make(Ticket::class, [
                'save' => function() { return true; },
                'attributes' => function() { return [
                        'id', 'column','row','amount','status','block_expired_at','expired_at','expired_at_formatted'
                    ];
                },
                'block_expired_at' => $blockedTime,
            ])
        ]);

        $this->assertEquals($tm->getModel()->block_expired_at, $blockedTime);

        $tm->unsetExpiredAt();

        $this->assertGreaterThanOrEqual(time(), $tm->getModel()->block_expired_at);
    }
}