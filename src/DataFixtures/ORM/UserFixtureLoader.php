<?php

namespace User\DataFixtures\ORM;

use User\Component\Doctrine\Common\DataFixtures\AbstractFixture;
use User\Entity\ORM\User;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class UserFixtureLoader.
 *
 * @package User\DataFixtures\ORM
 * @author  Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
class UserFixtureLoader extends AbstractFixture
{
    const REF_ENABLED_USER = 'ENABLED_USER';
    const REF_LOCKED_USER = 'LOCKED_USER';

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->populate($manager, self::REF_ENABLED_USER);
        $this->populate($manager, self::REF_LOCKED_USER);
    }

    /**
     * Loads the users to database and adds its to references.
     *
     * @param ObjectManager $manager
     * @param string        $ref
     */
    private function populate(ObjectManager $manager, string $ref)
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 5; $i++) {
            $user = new User();
            $user
                ->setCreatedAt($faker->dateTimeBetween('-30 days', 'now'))
                ->setUpdatedAt($faker->dateTimeBetween($user->getCreatedAt(), 'now'));

            switch ($ref) {
                case self::REF_ENABLED_USER:
                    $user->setStatus(User::STATUS_ENABLED);
                    break;
                case self::REF_LOCKED_USER:
                    $user->setStatus(User::STATUS_LOCKED);
                    break;
            }

            $manager->persist($user);

            $this->addReference($ref.$i, $user);
        }

        $manager->flush();
    }
}
