<?php

namespace Auth;

use Composer\Script\Event;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

/**
 * Composer scripts.
 *
 * @package Auth
 * @author Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
class ComposerScript
{
    /**
     * Initialize the databases.
     *
     * Creates dev and test databases. Works only with Postgres.
     *
     * @param Event $event
     */
    public static function initDatabase(Event $event)
    {
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        $rootDir = dirname($vendorDir);

        require_once $vendorDir.'/autoload.php';

        $devConfig = self::getConfig($rootDir.'/config/dev.php');
        $testConfig = self::getConfig($rootDir.'/config/test.php');

        foreach ([$devConfig, $testConfig] as $config) {
            $connection = self::createConnection(
                $config['db.postgres_options']['driver'],
                $config['db.postgres_options']['host'],
                $config['db.postgres_options']['user'],
                $config['db.postgres_options']['password']
            );
            self::createDatabase($connection, $config['db.postgres_options']['dbname']);
        }
    }

    /**
     * @param string $driver
     * @param string $host
     * @param string $user
     * @param string $password
     *
     * @return Connection
     */
    private static function createConnection(string $driver, string $host, string $user, string $password): Connection
    {
        $connection = DriverManager::getConnection([
            'driver' => $driver,
            'host' => $host,
            'dbname' => null,
            'user' => $user,
            'password' => $password,
        ]);

        return $connection;
    }

    /**
     * @param Connection $connection
     * @param string     $databaseName
     */
    private static function createDatabase(Connection $connection, string $databaseName)
    {
        $stmt = $connection->prepare('SELECT 1 FROM pg_database WHERE datname = :name');
        $stmt->bindValue(':name', $databaseName, \PDO::PARAM_STR);
        $stmt->execute();

        if (!$stmt->fetchColumn()) {
            $connection->exec("CREATE DATABASE \"{$databaseName}\"");
        }
    }

    /**
     * @param string $file
     *
     * @return array
     */
    private static function getConfig(string $file): array
    {
        $app = [];
        include($file);

        return $app;
    }
}
