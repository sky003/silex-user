<?php

namespace User\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;

/**
 * Migration for `account` table.
 *
 * @package User\Migrations
 * @author  Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
class Version20170827125627 extends AbstractMigration
{
    private $tableName = 'account';

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->createTable($this->tableName);

        $table->addColumn('id', Type::INTEGER, ['autoincrement' => true]);
        $table->addColumn('user_id', Type::INTEGER);
        $table->addColumn('email', Type::STRING);
        $table->addColumn('password', Type::STRING);
        $table->addColumn('status', Type::SMALLINT);
        $table->addColumn('created_at', Type::DATETIME);
        $table->addColumn('updated_at', Type::DATETIME, ['notnull' => false]);

        $table->setPrimaryKey(['id']);

        $table->addIndex(['user_id']);
        $table->addForeignKeyConstraint('user', ['user_id'], ['id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable($this->tableName);
    }
}
