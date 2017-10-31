<?php
// @codingStandardsIgnoreFile

namespace User\Tests\Acceptance;

use User\Tests\AcceptanceTester;

class IndexCest
{
    public function testIndex(AcceptanceTester $tester)
    {
        $tester->wantTo('make sure that web server works');
        $tester->sendGET('/');

        $tester->expectTo('see correct response');
        $tester->seeResponseCodeIs(200);
    }
}
