<?php
// @codingStandardsIgnoreFile

namespace User\Tests\Acceptance;

use User\Tests\AcceptanceTester;

class IndexCest
{
    public function testIndex(AcceptanceTester $I)
    {
        $I->wantTo('make sure that web server works');
        $I->sendGET('/');

        $I->expectTo('see correct response');
        $I->seeResponseCodeIs(200);
    }
}
