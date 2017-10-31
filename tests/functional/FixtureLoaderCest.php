<?php
// @codingStandardsIgnoreFile

namespace Anth\Tests\Functional;

use User\DataFixtures\ORM\AccountFixtureLoader;
use User\Tests\FunctionalTester;

class FixtureLoaderCest
{
    public function testLoadFixture(FunctionalTester $tester)
    {
        $fixture = $tester->loadFixture(new AccountFixtureLoader());

        $user = $fixture->getReference('ENABLED_ACCOUNT1')->getUser();
    }
}
