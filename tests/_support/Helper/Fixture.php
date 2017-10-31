<?php

declare(strict_types = 1);

namespace User\Tests\Helper;

use User\Component\Doctrine\Common\DataFixtures\ContainerAwareInterface;
use Codeception\Module;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\SharedFixtureInterface;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;
use Pimple\Psr11\Container;

/**
 * Helper to manage the database fixtures.
 *
 * @package User\Tests\Helper
 * @author Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
class Fixture extends Module
{
    /**
     * Loads the fixture to database.
     *
     * @param \Doctrine\Common\DataFixtures\SharedFixtureInterface $fixtureLoader
     *
     * @return \Doctrine\Common\DataFixtures\SharedFixtureInterface|\User\Component\Doctrine\Common\DataFixtures\AbstractFixture
     */
    public function loadFixture(SharedFixtureInterface $fixtureLoader): SharedFixtureInterface
    {
        if ($fixtureLoader instanceof ContainerAwareInterface) {
            $fixtureLoader->setContainer(
                new Container(
                    // https://github.com/Codeception/Codeception/issues/4531
                    //$this->getModule('Silex')->app
                    $GLOBALS['app']
                )
            );
        }

        $entityManager = $this->createEntityManager();

        $entityManager->getEventManager()->addEventListener('loadClassMetadata', new class() {
            /**
             * Event listener for class metadata loading.
             *
             * @param LoadClassMetadataEventArgs $eventArgs
             */
            public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
            {
                /**
                 * @var \Doctrine\ORM\Mapping\ClassMetadata $metadata
                 */
                $metadata = $eventArgs->getClassMetadata();
                // `Faker` generates all the fields for fixtures, including `createdAt` and `updatedAt` values.
                // So the lifecycle callbacks should be disabled.
                $metadata->setLifecycleCallbacks([]);
            }
        });

        $purger = new ORMPurger();
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);
        $loader = new Loader();
        $loader->addFixture($fixtureLoader);
        $executor = new ORMExecutor($entityManager, $purger);
        $executor->execute($loader->getFixtures(), false);

        return $fixtureLoader;
    }

    /**
     * @return EntityManagerInterface
     */
    private function createEntityManager(): EntityManagerInterface
    {
        $entityPaths = $this->_getConfig('entity_paths');

        $driver = new AnnotationDriver(
            new AnnotationReader(),
            $entityPaths
        );
        $config = Setup::createConfiguration(true);
        $config->setMetadataDriverImpl($driver);
        $connection = DriverManager::getConnection([
            'pdo' => $this->getModule('Db')->dbh,
        ]);
        $entityManager = EntityManager::create($connection, $config);

        return $entityManager;
    }
}
