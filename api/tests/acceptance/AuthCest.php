<?php

namespace api\tests\acceptance;

use api\tests\acceptanceTester;
use common\fixtures\ClientFixture;
use common\fixtures\TokenFixture;

class AuthCest
{

    public function _before(AcceptanceTester $I){
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

    public function index(AcceptanceTester $I)
    {
        $I->sendGET('/');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains("api");
    }

    public function badMethod(AcceptanceTester $I)
    {
        $I->sendGET('/login');
        $I->seeResponseCodeIs(405);
        $I->seeResponseIsJson();
    }

    public function wrongCredentials(AcceptanceTester $I)
    {
        $I->sendPOST('/login', [
            'username' => 'bayer.hudson',
            'password' => 'password_',
        ]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContainsJson([
            'error' => 'Incorrect username or password.'
        ]);
    }

    public function success(AcceptanceTester $I)
    {
        $I->sendPOST('/login', [
            'username' => 'bayer.hudson',
            'password' => 'password_0',
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$..token');
        $I->seeResponseJsonMatchesJsonPath('$..expired');
    }
}
