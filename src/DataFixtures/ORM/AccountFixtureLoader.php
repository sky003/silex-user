<?php

namespace User\DataFixtures\ORM;

use User\Component\Doctrine\Common\DataFixtures\AbstractFixture;
use User\Component\Doctrine\Common\DataFixtures\ContainerAwareInterface;
use User\Entity\ORM\Account;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Psr\Container\ContainerInterface;
use Symfony\Component\Security\Core\User\User;

/**
 * Loads the account fixtures.
 *
 * @package User\DataFixtures\ORM
 * @author  Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
class AccountFixtureLoader extends AbstractFixture implements DependentFixtureInterface, ContainerAwareInterface
{
    const REF_ENABLED_ACCOUNT = 'ENABLED_ACCOUNT';
    const REF_LOCKED_ACCOUNT = 'LOCKED_ACCOUNT';

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->populate($manager, self::REF_ENABLED_ACCOUNT);
        $this->populate($manager, self::REF_LOCKED_ACCOUNT);
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies(): array
    {
        return [
            UserFixtureLoader::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Loads the accounts to database and adds its to references.
     *
     * @param ObjectManager $manager
     * @param string        $ref
     */
    private function populate(ObjectManager $manager, string $ref)
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 5; $i++) {
            $account = new Account();
            $account
                ->setEmail($faker->unique()->email)
                // Password is the same to email.
                ->setPassword(
                    $this->container->get('security.encoder_factory')
                        ->getEncoder(new User('u', 'p')) // TODO: Use correct user when it will be implemented.
                        ->encodePassword($account->getEmail(), null)
                )
                ->setCreatedAt($faker->dateTimeBetween('-30 days', 'now'))
                ->setUpdatedAt($faker->dateTimeBetween($account->getCreatedAt(), 'now'));

            switch ($ref) {
                case self::REF_ENABLED_ACCOUNT:
                    $account->setStatus(Account::STATUS_ENABLED);
                    $account->setUser(
                        $this->getReference(UserFixtureLoader::REF_ENABLED_USER.$i)
                    );
                    break;
                case self::REF_LOCKED_ACCOUNT:
                    $account->setStatus(Account::STATUS_LOCKED);
                    $account->setUser(
                        $this->getReference(UserFixtureLoader::REF_LOCKED_USER.$i)
                    );
                    break;
            }

            $manager->persist($account);

            $this->addReference($ref.$i, $account);
        }

        $manager->flush();
    }
}
