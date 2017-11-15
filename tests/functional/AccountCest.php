<?php
// @codingStandardsIgnoreFile

namespace User\Tests\Functional;

use User\DataFixtures\ORM\AccountFixtureLoader;
use User\Entity\ORM\Account;
use User\Tests\FunctionalTester;

class AccountCest
{
    public function testCreate(FunctionalTester $I)
    {
        $I->loadFixture(new AccountFixtureLoader());

        $data = [
            'email' => 'john.doe@example.com',
            'password' => 'strong-password',
            'status' => (string) Account::STATUS_ENABLED,
        ];

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST(
            $I->grabService('url_generator')->generate('accounts/create'),
            $data
        );

        $I->expectTo('see the response is correct');
        $I->seeResponseCodeIs(201);
        $I->seeResponseContainsJson();
    }
}
