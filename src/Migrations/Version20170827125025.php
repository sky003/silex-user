<?php

namespace Auth\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;

/**
 * Migrations for `user` table.
 *
 * @package Auth\Migrations
 * @author  Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
class Version20170827125025 extends AbstractMigration
{
    private $tableName = 'user';

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->createTable($this->tableName);

        $table->addColumn('id', Type::INTEGER, ['autoincrement' => true]);
        $table->addColumn('status', Type::SMALLINT);
        $table->addColumn('created_at', Type::DATETIME);
        $table->addColumn('updated_at', Type::DATETIME, ['notnull' => false]);

        $table->setPrimaryKey(['id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable($this->tableName);
    }
}
