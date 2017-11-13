<?php
// @codingStandardsIgnoreFile

namespace User\Tests\Functional;

use User\Tests\FunctionalTester;

class AccountCest
{
    public function testCreate(FunctionalTester $I)
    {
        $data = [

        ];

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST(
            $I->grabService('url_generator')->generate('accounts/create'),
            $data
        );
    }
}
