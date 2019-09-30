<?php
namespace api\tests\acceptance;

use api\tests\acceptanceTester;
use common\fixtures\ClientFixture;
use common\fixtures\TokenFixture;

class TicketCest
{
    public function _before(acceptanceTester $I)
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

    public function access(acceptanceTester $I)
    {
        $I->sendGET('/ticket');
        $I->seeResponseCodeIs(401);
    }

    public function authenticated(acceptanceTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/ticket');
        $I->seeResponseCodeIs(200);

        $I->seeResponseJsonMatchesJsonPath('$...block_expired_at');
    }
}
