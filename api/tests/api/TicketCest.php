<?php namespace api\tests;

use Codeception\Util\HttpCode;
use common\fixtures\ClientFixture;
use common\fixtures\TokenFixture;

class TicketCest
{
    /**
     * @param ApiTester $I
     */
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
        ]);
    }

    // tests
    public function notAuthenticatedTest(ApiTester $I)
    {
        $I->sendGET('/ticket');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    public function authenticatedTest(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/ticket');
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function isJsonTest(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/ticket');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();

        $I->seeResponseJsonMatchesJsonPath('$..block_expired_at');
    }
}
