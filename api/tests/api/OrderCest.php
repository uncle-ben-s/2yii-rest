<?php namespace api\tests;
use api\tests\ApiTester;
use Codeception\Util\HttpCode;
use common\fixtures\ClientFixture;
use common\fixtures\OrderFixture;
use common\fixtures\TokenFixture;

class OrderCest
{
    public function _before(ApiTester $I)
    {
        $I->haveFixtures([
            'user'  => [
                'class'    => ClientFixture::class,
                'dataFile' => codecept_data_dir() . 'client.php'
            ],
            'token' => [
                'class'    => TokenFixture::class,
                'dataFile' => codecept_data_dir() . 'token.php'
            ],
            'order' => [
                'class'    => OrderFixture::class,
                'dataFile' => codecept_data_dir() . 'order.php'
            ],
        ]);
    }

    // tests
    public function indexActionForbiddenTest(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/orders');
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        $I->seeResponseIsJson();

        $I->seeResponseJsonMatchesJsonPath('$error');
    }

    public function viewActionTest(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/orders/2');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();

        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'client_id' => 'integer',
            'status' => 'string',
            'ticket_cnt' => 'integer',
            'amount' => 'integer',
        ]);

        $I->seeResponseEquals('{"ticket_cnt":0,"amount":0,"id":2,"client_id":1,"status":"open"}');
    }

    public function viewActionOrderClosedTest(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/orders/3');
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();

        $I->seeResponseMatchesJsonType([
            'error' => 'string',
        ]);

        $I->seeResponseEquals('{"error":"OrderClosed"}');
    }

    public function viewActionOrderNotFoundTest(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/orders/5');
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();

        $I->seeResponseMatchesJsonType([
            'error' => 'string',
        ]);

        $I->seeResponseEquals('{"error":"OrderNotFound"}');
    }

    public function viewActionOrderBelongsToAnotherClientTest(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/orders/1');
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();

        $I->seeResponseMatchesJsonType([
            'error' => 'string',
        ]);

        $I->seeResponseEquals('{"error":"OrderBelongsToAnotherClient"}');
    }

    public function createActionTest(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendPOST('/orders');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();

        $I->seeResponseMatchesJsonType([
            "id" => "integer",
            "client_id" => "integer",
            "status" => "string",
        ]);

        $I->seeResponseEquals('{"id":"2","client_id":"1","status":"open"}');
    }
}
