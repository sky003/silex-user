<?php
// @codingStandardsIgnoreFile

namespace Anth\Tests\Functional;

use Auth\Tests\FunctionalTester;

class IndexCest
{
    public function testIndex(FunctionalTester $tester)
    {
        $tester->wantTo('make sure that bootstrap works');
        $tester->sendGET('/');

        $tester->expectTo('see correct response');
        $tester->seeResponseCodeIs(200);
    }
}
